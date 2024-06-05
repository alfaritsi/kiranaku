<?php
$this->load->view('header')
?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title"><strong><?php echo $title; ?></strong></h3>

                        <div class="btn-group pull-right">
                            <button type="button" class="btn btn-sm btn-success" id="export_excel"><i class="fa fa-file-excel-o"></i> Export Excel
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <form name="filter" method="post">
                            <div class="row">
                                <div class="col-md-2 col-md-offset-6">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label class="input-group-addon" for="id_plant">Pabrik</label>
                                            <select class="form-control select2" name="plant" id="plant" data-placeholder="Pilih Pabrik" data-allow-clear="true">
                                                <option></option>
                                                <?php foreach ($plants as $plant) : ?>
                                                    <option value="<?php echo $plant->plant ?>" <?php echo ($plant->plant == $plant_selected) ? 'selected' : ''; ?>><?php echo $plant->plant ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="input-group input-daterange" id="filter-date">
                                            <label class="input-group-addon" for="tanggal-awal_filter">Periode Perjanjian</label>
                                            <input type="text" id="tanggal_awal_filter" name="tanggal_awal" value="<?php echo $this->generate->generateDateFormat($tanggal_awal); ?>" class="form-control" autocomplete="off">
                                            <label class="input-group-addon" for="tanggal-awal_filter">-</label>
                                            <input type="text" id="tanggal_akhir_filter" name="tanggal_akhir" value="<?php echo $this->generate->generateDateFormat($tanggal_akhir); ?>" class="form-control" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <table class="table table-bordered table-striped" id="spk-table" data-page-length="10" style="font-size: 14px;">
                            <thead>
                                <tr>
                                    <th width="5%">Pabrik</th>
                                    <th>Jenis Perjanjian</th>
                                    <th>Nomor Perjanjian</th>
                                    <th>Perihal</th>
                                    <th>Tanggal Buat</th>
                                    <th width="10%">Tanggal</th>
                                    <th>Vendor</th>
                                    <th width="5%">Status</th>
                                    <th width="5%">Dokumen<br>Final</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($list as $dt) {
                                    $status = "";
                                    switch ($dt->status) {
                                        case 'confirmed':
                                            $status = '<div class="badge bg-blue">CONFIRMED</div>';
                                            $status += '<br><small>Menunggu dokumen final draft</small>';
                                            break;
                                        case 'finaldraft':
                                            $status = '<div class="badge bg-purple">FINAL DRAFT</div>';
                                            break;
                                        case 'completed':
                                            $status = '<div class="badge bg-green">COMPLETED</div>';
                                            break;
                                        case 'drop':
                                            $status = '<div class="badge bg-red">DROP</div>';
                                            break;
                                        case 'cancelled':
                                            $status = '<div class="badge bg-red">CANCELLED</div>';
                                            $status .= '<br><small>Dicancel oleh ' . $dt->status_spk_cancel . '</small>';
                                            break;
                                        default:
                                            $sts = ($dt->status_spk) ? substr($dt->status_spk, 0, -1) : "";
                                            $status = '<div class="badge bg-yellow">ON PROGRESS</div>';
                                            $status .= '<br><small>Sedang diproses oleh ' . $sts . '</small>';
                                            break;
                                    }
                                    $na = ($dt->na == 'n') ? "<i class='fa fa-check-square text-success'></i>" : "<i class='fa fa-minus-square text-danger'></i>";
                                    echo "<tr>";
                                    echo "<td>" . $dt->plant . "</td>";
                                    echo "<td>" . $dt->jenis_spk . "</td>";
                                    echo "<td>" . $dt->nomor_spk . "</td>";
                                    echo "<td>" . $dt->perihal . "</td>";
                                    echo "<td>" . $dt->tanggal_buat_format . "</td>";
                                    echo "<td class='text-nowrap'>"
                                        . "<b>Perjanjian :</b> " . $this->generate->generateDateFormat($dt->tanggal_perjanjian) . "<br/>"
                                        . "<b>Berlaku :</b> " . $this->generate->generateDateFormat($dt->tanggal_berlaku_spk) . "<br/>"
                                        . "<b>Berakhir :</b> " . $this->generate->generateDateFormat($dt->tanggal_berakhir_spk) . "<br/>"
                                        . (isset($dt->tanggal_approve) ? "<b>Input :</b> " . $this->generate->generateDateFormat($dt->tanggal_approve) : '')
                                        . "</td>";
                                    echo "<td>" . $dt->nama_vendor . "</td>";
                                    echo "<td>" . $status . "<a href='javascript:void(0)' class='spk-history' data-id_spk='" . $dt->id_spk . "'><span class='badge bg-light-blue'><i class='fa fa-search'></i> Lihat History</span></a></td>";

                                    $dt->final = null;
                                    if (isset($dt->files)) {
                                        $dt->files = site_url('assets/' . $dt->files);
                                        $dt->final = "<a href='$dt->files' data-fancybox><span class='badge bg-red-gradient'><i class='fa fa-file-pdf-o'></i></span> </a>";
                                    }

                                    echo "<td>"
                                        . $dt->final
                                        . "</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php $this->load->view('transaction/includes/modal_history') ?>
<?php $this->load->view('footer') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/apps/css/spk/spk.global.css" />
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.css" />
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css" />
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker3.min.css" />
<script src="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/spk/spk.global.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/spk/report_dokumen.js?<?php echo time(); ?>"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>