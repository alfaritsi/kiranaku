<?php
/**
 * @application  : ESS BAK Pengajuan - View
 * @author       : Octe Reviyanto Nugroho
 * @contributor  :
 *     1. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     2. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     etc.
 */

$this->load->view('header')
?>

<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title"><strong><?php echo $title ?></strong></h3>
                    </div>
                    <div class="box-body">
                        <!--<form name="filter-laporan-cuti" method="post">
                            <div class="row">
                                <div class="col-md-4 col-md-offset-8">
                                    <div class="form-group">
                                        <div class="input-group input-daterange" id="filter-date">
                                            <label class="input-group-addon" for="tanggal-awal">Tanggal</label>
                                            <input type="text" id="tanggal_awal" name="tanggal_awal" value="<?php /*echo $this->generate->generateDateFormat($tanggal_awal);*/ ?>" class="form-control" autocomplete="off">
                                            <label class="input-group-addon" for="tanggal-awal">-</label>
                                            <input type="text" id="tanggal_akhir" name="tanggal_akhir" value="<?php /*echo $this->generate->generateDateFormat($tanggal_akhir);*/ ?>" class="form-control" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>-->
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered table-responsive my-datatable-extends-order table-striped table-hover"
                                       id="table-data-bak"
                                       data-page-length='10' data-order='[[0,"desc"]]'>
                                    <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Bagian</th>
                                        <?php foreach ($periode as $dt) : ?>
                                            <th><?php echo $this->generate->generateDateFormat($dt) ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($list_data as $data): ?>
                                        <tr data-id="<?php echo $data->enId; ?>">
                                            <td><?php echo $data->nama ?></td>
                                            <td>
                                                <?php
                                                if ($data->ho == 'y') {
                                                    $bagian = (empty($data->nama_departemen)) ? $data->nama_divisi : $data->nama_departemen;
                                                } else {
                                                    $bagian = (empty($data->nama_seksi)) ? $data->nama_departemen : $data->nama_seksi;
                                                    $bagian = (empty($bagian)) ? $data->nama_sub_divisi : $bagian;
                                                    $bagian = (empty($bagian)) ? $data->nama_pabrik : $bagian;
                                                }
                                                echo $bagian;
                                                ?>
                                            </td>
                                            <?php
                                            foreach ($periode as $dt) {
                                                $cico = "Absen";
                                                foreach ($data->bak as $bak):
                                                    if ($bak->tanggal_absen == $dt)
                                                        if ($bak->tipe == '-')
                                                            $cico = $bak->absen_masuk . "-" . $bak->absen_keluar;
                                                        else if ($bak->tipe == 'L')
                                                            $cico = 'Libur';
                                                        else if ($bak->tipe == '0110' or $bak->tipe == '0120')
                                                            $cico = 'Cuti';
                                                        else
                                                            $cico = 'Ijin';
                                                endforeach;

                                                echo "<td>$cico</td>";
                                            }
                                            ?>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $this->load->view('footer') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker3.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/moment/bootstrap-datetimepicker.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/apps/css/ess/ess-global.css"/>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/moment/bootstrap-datetimepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/ess/ess-global.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/ess/bak_data.js"></script>