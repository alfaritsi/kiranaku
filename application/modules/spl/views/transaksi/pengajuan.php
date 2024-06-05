<!--
/*
@application  : SPL
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
                        <h3 class="box-title"><strong>Data SPL</strong></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form name="filter-data-spk" method="post">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Pabrik: </label>
                                        <select class="form-control select2" multiple="multiple" id="filter_plant" name="filter_plant" data-allowclear="true">
                                            <?php
                                            foreach ($pabrik as $plant) :
                                                echo "<option value='" . $plant . "'>" . $plant . "</option>";
                                            endforeach;
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Tahun: </label>
                                        <select class="form-control select2" multiple="multiple" id="filter_tahun" name="filter_tahun" data-allowclear="true">
                                            <?php
                                            if ($tahun) {
                                                $output = '';
                                                foreach ($tahun as $dt) {
                                                    $output .= "<option value='" . $dt->tahun . "'";
                                                    if ($dt->tahun == date('Y')) {
                                                        $output .=  "selected";
                                                    }
                                                    $output .= ">" . $dt->tahun . "</option>";
                                                }
                                                echo $output;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Departemen: </label>
                                        <select class="form-control select2" multiple="multiple" id="filter_departemen" name="filter_departemen" data-allowclear="true">
                                            <?php
                                            foreach ($departemen as $dt) {
                                                echo "<option value='" . $dt->id . "'>" . $dt->nama . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <?php
                                if ($tipe_list == "list") :
                                ?>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select class="select2 form-control" id="filter_status" name="status" data-allowclear="true" multiple="multiple">
                                                <option value="onprogress" selected>On Progress</option>
                                                <option value="finish">Finish</option>
                                                <option value="completed">Completed</option>
                                                <option value="rejected">Rejected</option>
                                            </select>
                                        </div>
                                    </div>
                                <?php
                                endif;
                                ?>
                            </div>
                        </form>
                    </div>
                    <!-- /.box-filter -->
                    <div class="box-body">
                        <table class="table table-bordered table-hover table-striped my-datatable-extends-order" id="sspTable" data-ordering="true" data-scrollx="false" data-bautowidth="true" data-pagelength="10">
                            <thead>
                                <tr>
                                    <th>No. SPL</th>
                                    <th>Pabrik</th>
                                    <th>Tanggal Buat</th>
                                    <th width="10%">Tanggal Pengajuan</th>
                                    <th width="10%">Tanggal SPL</th>
                                    <th>Departemen</th>
                                    <th>Seksi</th>
                                    <th>Status</th>
                                    <th width="5%"></th>
                                </tr>
                            </thead>
                            <!-- <tbody>
                                <tr>
                                    <td>ABL1</td>
                                    <td>10.10.2022</td>
                                    <td>DEPARTEMEN QUALITY</td>
                                    <td>SEKSIE QUALITY CONTROL</td>
                                    <td>
                                        <div class="badge bg-yellow">ON PROGRESS</div>
                                        <br><small>Sedang diproses oleh Kasie</small>
                                    </td>
                                    <td>
                                        <div class='btn-group'>
                                            <button type='button' class='btn btn-default btn-sm dropdown-toggle' data-toggle='dropdown'><span class='fa fa-caret-down'></span></button>
                                            <ul class='dropdown-menu pull-right'>
                                                <li><a href='<?php echo base_url() . "spl/transaksi/detail"; ?>' target='_blank' class='detail'><i class='fa fa-search'></i> Detail</a></li>
                                                <li><a href='<?php echo base_url() . "spl/transaksi/edit"; ?>' target='_blank' class='edit'><i class='fa fa-edit'></i> Edit</a></li>
                                                <li><a href='#' class='delete' data-delete=''><i class='fa fa-trash-o'></i> Hapus</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>ABL1</td>
                                    <td>11.10.2022</td>
                                    <td>DEPARTEMEN PABRIK</td>
                                    <td>SEKSIE PRODUKSI</td>
                                    <td>
                                        <div class="badge bg-green">Finish</div>
                                        <br><small>Menunggu Realisasi</small>
                                    </td>
                                    <td>
                                        <div class='btn-group'>
                                            <button type='button' class='btn btn-default btn-sm dropdown-toggle' data-toggle='dropdown'><span class='fa fa-caret-down'></span></button>
                                            <ul class='dropdown-menu pull-right'>
                                                <li><a href='<?php echo base_url() . "spl/transaksi/detail"; ?>' target='_blank' class='detail'><i class='fa fa-search'></i> Detail</a></li>
                                                <li><a href='<?php echo base_url() . "spl/transaksi/detail"; ?>' target='_blank' class='edit'><i class='fa fa-edit'></i> Edit</a></li>
                                                <li><a href='#' class='delete' data-delete=''><i class='fa fa-trash-o'></i> Hapus</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>ABL1</td>
                                    <td>12.10.2022</td>
                                    <td>DEPARTEMEN PABRIK</td>
                                    <td>SEKSIE PRODUKSI</td>
                                    <td>
                                        <div class="badge bg-blue">Completed</div>
                                    </td>
                                    <td>
                                        <div class='btn-group'>
                                            <button type='button' class='btn btn-default btn-sm dropdown-toggle' data-toggle='dropdown'><span class='fa fa-caret-down'></span></button>
                                            <ul class='dropdown-menu pull-right'>
                                                <li><a href='<?php echo base_url() . "spl/transaksi/detail"; ?>' target='_blank' class='detail'><i class='fa fa-search'></i> Detail</a></li>
                                                <li><a href='<?php echo base_url() . "spl/transaksi/detail"; ?>' target='_blank' class='edit'><i class='fa fa-edit'></i> Edit</a></li>
                                                <li><a href='#' class='delete' data-delete=''><i class='fa fa-trash-o'></i> Hapus</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>ABL1</td>
                                    <td>01.10.2022</td>
                                    <td>DEPARTEMEN PABRIK</td>
                                    <td>SEKSIE PRODUKSI</td>
                                    <td>
                                        <div class="badge bg-red">Rejected</div>
                                        <br><small>Ditolak Oleh Manager Kantor</small>
                                    </td>
                                    <td>
                                        <div class='btn-group'>
                                            <button type='button' class='btn btn-default btn-sm dropdown-toggle' data-toggle='dropdown'><span class='fa fa-caret-down'></span></button>
                                            <ul class='dropdown-menu pull-right'>
                                                <li><a href='<?php echo base_url() . "spl/transaksi/detail"; ?>' target='_blank' class='detail'><i class='fa fa-search'></i> Detail</a></li>
                                                <li><a href='<?php echo base_url() . "spl/transaksi/detail"; ?>' target='_blank' class='edit'><i class='fa fa-edit'></i> Edit</a></li>
                                                <li><a href='#' class='delete' data-delete=''><i class='fa fa-trash-o'></i> Hapus</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            </tbody> -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/spl/transaksi/pengajuan.js?<?php echo time(); ?>"></script>