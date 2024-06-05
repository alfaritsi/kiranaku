<?php
/**
 * @application  : ESS Medical Print - View
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
    <section class="content invoice cetak-medical">
        <div class="row">
            <div class="col-sm-12">
                <div class="no-print">
                    <div class="alert alert-info">
                        <b>Catatan:</b> Halaman ini dipergunakan untuk mencetak Form KLAIM BENEFIT, Silakan tekan tombol
                        <b>Cetak</b> di bawah halaman untuk mencetak Form KLAIM BENEFIT,.
                    </div>
                </div>
                <img src='<?php echo base_url() ?>assets/apps/img/logo-km-group.png'><br>
                <div style="text-align: center;">
                    <b style='font-size:16px;'><u>PENGAJUAN KLAIM BENEFIT</u></b><br>
                    <b>NO: <?php echo $pengajuan['data']->nomor; ?></b>
                </div>
                <br>
                <!-- Table row -->
                <div class='row'>
                    <div class='col-xs-12 table-responsive'>
                        <table class='table table-bordered0'>
                            <tbody>
                            <tr>
                                <td width='20%'>Tanggal Pengajuan</td>
                                <td colspan='4'>
                                    : <?php echo $this->generate->generateDateFormat($pengajuan['data']->tanggal_buat); ?></td>
                            </tr>
                            <tr>
                                <td>NIK</td>
                                <td colspan='4'>: <?php echo $pengajuan['data']->nik; ?></td>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td colspan='4'>: <?php echo $pengajuan['data']->nama_karyawan; ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $pengajuan['data']->fbk_jenis_nama; ?></td>
                                <td colspan="4">:</td>
                            </tr>
                            <?php
                            $total = 0;
                            foreach ($pengajuan['data']->kwitansi as $kwitansi) :
                                $total += $kwitansi->amount_kwitansi;
                                ?>
                                <tr>
                                    <td width='25%'></td>
                                    <td width='10%'>&nbsp;&nbsp;&nbsp;No.Detail:&nbsp;<?php echo $kwitansi->nomor; ?></td>
                                    <td width='15%'>No:&nbsp;<?php echo $kwitansi->nomor_kwitansi ?></td>
                                    <td>Tgl:&nbsp;<?php echo $kwitansi->tanggal_kwitansi ?></td>
                                    <td>&nbsp;&nbsp;&nbsp;<?php echo $this->less->convert_rupiah($kwitansi->amount_kwitansi) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td width='20%'>Total Kwitansi</td>
                                <td colspan="3" width='20%'>:</td>
                                <td>&nbsp;&nbsp;&nbsp;<?php echo $this->less->convert_rupiah($total) ?></td>
                            </tr>
                            <?php if ($pengajuan['data']->fbk_jenis == "inap"):
                                $diff = $pengajuan['data']->sisa_plafon_awal / $pengajuan['data']->biaya_kamar;
                                if ($diff > 1)
                                    $estimasi = $pengajuan['data']->total_kwitansi;
                                else
                                    $estimasi = $pengajuan['data']->total_kwitansi * $diff;
                                ?>
                                <tr>
                                    <td width='20%'>Estimasi Dibayar Perusahaan</td>
                                    <td colspan="3" width='20%'>:</td>
                                    <td>&nbsp;&nbsp;&nbsp;<?php echo $this->less->convert_rupiah($estimasi) ?></td>
                                </tr>
                                <tr>
                                    <td width='20%'>Estimasi Dibayar Karyawan</td>
                                    <td colspan="3" width='20%'>:</td>
                                    <td>&nbsp;&nbsp;&nbsp;<?php echo $this->less->convert_rupiah($total - $estimasi) ?></td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td colspan="3"></td>
                                <td width='25%'>TGL DITERIMA</td>
                                <td>:</td>
                            </tr>
                            <tr>
                                <td colspan="3"></td>
                                <td width='25%'>TGL DIVERIFIKASI</td>
                                <td>:</td>
                            </tr>
                            <tr>
                                <td colspan="3"></td>
                                <td>DITERIMA & DIVERIFIKASI</td>
                                <td>:</td>
                            </tr>
                            <tr>
                                <td rowspan='5'>FO13a/HRG/08/2017, rev 0</td>
                            </tr>
                            </tbody>
                        </table>
                    </div><!-- /.col -->
                </div><!-- /.row -->
                <!-- this row will not appear when printing -->
                <div class='row no-print'>
                    <div class='col-xs-12 text-center'>
                        <button class='btn btn-success btn-sm' onclick='window.print();'><i class='fa fa-print'></i>
                            Cetak
                        </button>
                        <a class='btn btn-primary  btn-sm' href="<?php echo $_SERVER['HTTP_REFERER'] ?>"><i
                                    class='fa fa-reply'></i> Kembali</a>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $this->load->view('footer') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/apps/css/ess/ess-global.css"/>
<script src="<?php echo base_url() ?>assets/apps/js/ess/ess-global.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/ess/medical_cetak.js"></script>
