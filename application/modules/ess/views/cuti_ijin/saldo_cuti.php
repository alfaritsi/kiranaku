<?php
/**
 * @application  : ESS Cuti & Ijin - Saldo Cuti View
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
                        <h3 class="box-title"><strong>Saldo Cuti Karyawan</strong></h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-striped my-datatable-extends-order"
                               data-page-length='25' data-order='[[1,"asc"]]'>
                            <thead>
                            <tr>
                                <th data-orderable="false" width="5%"></th>
                                <th width="10%">NIK</th>
                                <th>Nama Karyawan</th>
                                <th width="15%" align="right">&sum; Saldo saat ini</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($karyawans as $karyawan) : ?>
                                <tr>
                                    <td>
                                        <a href="#cuti-<?php echo $karyawan->nik; ?>" role="button"
                                           data-toggle="collapse"
                                           class="text-success" data-nik="<?php echo $karyawan->nik; ?>">
                                            <i class="fa fa-plus-square"></i>
                                        </a>
                                    </td>
                                    <td><?php echo $karyawan->nik ?></td>
                                    <td>
                                        <?php echo ucwords(strtolower($karyawan->nama)) ?>
                                        <div id="cuti-<?php echo $karyawan->nik; ?>" class="collapse">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>Jenis Cuti</th>
                                                    <th>Jumlah</th>
                                                    <th>Terpakai</th>
                                                    <th>Tanggal Awal</th>
                                                    <th>Tanggal Akhir</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach ($karyawan->saldo_cuti as $saldo):?>
                                                    <tr>
                                                        <td><?php echo ucwords(strtolower($saldo->nama));?></td>
                                                        <td><?php echo $saldo->jumlah;?> Hari</td>
                                                        <td><?php echo $saldo->terpakai;?> Hari</td>
                                                        <td><?php echo $this->generate->generateDateFormat($saldo->tanggal_awal);?></td>
                                                        <td><?php echo $this->generate->generateDateFormat($saldo->tanggal_akhir);?></td>
                                                    </tr>
                                                <?php endforeach;?>
                                                </tbody>
                                            </table>
                                        </div>

                                    </td>
                                    <td><?php echo $karyawan->sisa_cuti['sisa'] ?> Hari</td>
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
<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/ess/cuti_saldo.js"></script>
