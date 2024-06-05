<?php $this->load->view('header') ?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
                    </div>
                    <div class="box-body">
                        <form class="form-horizontal margin-bottom" method="post">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Tanggal PO</label>
                                    <div class="input-group input-daterange" id="filter-date">
                                        <label class="input-group-addon" for="tanggal-awal_filter">Tanggal</label>
                                        <input type="text" id="tanggal_awal_filter" name="tanggal_awal"
                                               value="<?php echo $tanggal_awal->format('d.m.Y'); ?>"
                                               class="form-control" autocomplete="off">
                                        <label class="input-group-addon" for="tanggal-awal_filter">-</label>
                                        <input type="text" id="tanggal_akhir_filter" name="tanggal_akhir"
                                               value="<?php echo $tanggal_akhir->format('d.m.Y'); ?>"
                                               class="form-control" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label>Pabrik Pemesan</label>
                                    <div class="input-group">
                                        <select class="form-control select2"
                                                name="pabrik[]" id="pabrik"
                                                data-placeholder="Pilih pabrik" multiple>
                                            <option></option>
                                            <?php foreach ($pabriks as $pabrik): ?>
                                                <option value="<?php echo $pabrik['id_plant'] ?>" <?php echo $pabrik['selected'] ? 'selected' : ''; ?>>
                                                    <?php echo $pabrik['id_plant'] . ' - ' . $pabrik['plant'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label>Status</label>
                                    <div class="input-group col-md-12">
                                        <select class="form-control select2"
                                                name="status" id="status"
                                                data-placeholder="Pilih status">
                                            <option value="0" <?php echo ($status_filter == 0)?'selected':''; ?>>Semua</option>
                                            <option value="1" <?php echo ($status_filter == 1)?'selected':''; ?>>SPK Tidak Lengkap</option>
                                            <option value="2" <?php echo ($status_filter == 2)?'selected':''; ?>>SPK Lengkap</option>
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <hr/>
                        <table class="table my-datatable-extends-order table-bordered table-hover" id="spk-table"
                               data-page-length="10" data-order='[[0,"asc"]]'>
                            <thead>
                            <tr>
                                <th width="5%">No SO</th>
                                <th width="20%">Pabrik Pemesan</th>
                                <th width="15%">No PO</th>
                                <th width="15%">No PI</th>
                                <th width="10%">Tanggal PO</th>
                                <th width="5%">Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($list as $i => $dt): ?>
                                <tr class="item-detail" data-detail="<?php echo $dt['no_so'] ?>">
                                    <td class="text-center">
                                        <?php echo $dt['no_so']; ?>
                                        <?php $this->load->view('monitoring/_table_so_items', array('items' => $dt['items'], 'no_so' => $dt['no_so'])); ?>
                                    </td>
                                    <td><?php echo $dt['plant']; ?></td>
                                    <td class="text-center"><?php echo $dt['no_po']; ?></td>
                                    <td class="text-center"><?php echo $dt['no_pi']; ?></td>
                                    <td class="text-center"><?php echo date_create($dt['tanggal_po'])->format('d.m.Y'); ?></td>
                                    <td class="text-center">
                                        <span class="<?php echo $dt['progress_color']?>">
                                            <?php echo $dt['progress'] ?> %
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $this->load->view('monitoring/_modal_detail_so_item', array()) ?>
<?php $this->load->view('footer') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker3.min.css"/>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/nusira/monitoring/list-so.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<style>
    .popover {
        max-width: 90vw;
    }

    .item-detail {
        cursor: pointer;
    }

    legend {
        font-size: 18px;
    }

    .modal-title {
        font-size: 20px;
    }
    #spk-table .box-title{
        font-size: 16px;
    }

    .table-so-detail .btn-default {
        background-color: #efefef;
    }
</style>