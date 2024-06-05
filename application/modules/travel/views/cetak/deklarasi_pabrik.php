<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo strtoupper($module) ?> | PT. Kirana Megatara Tbk</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="shortcut icon" type="image/png" href="<?php echo base64_encode(file_get_contents(base_url() . 'assets/apps/img/logo-sm.png')); ?>" />
    <style>
        @page {
            margin: 70px 25px;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
        }

        body {
            margin-top: 0.5cm;
            margin-left: 0cm;
            margin-right: 0cm;
            margin-bottom: 0cm;
        }

        header {
            position: fixed;
            top: -50px;
            left: 0px;
            right: 0px;
            height: 60px;

            /** Extra personal styles **/
            /*background-color: #03a9f4;*/
            /*color: white;*/
            text-align: left;
            line-height: 20px;
            /* font-family: 'Segoe UI'; */
            font-size: 10px;
        }

        .logo {
            position: absolute;
            top: 0px;
            right: 0px;
            width: 150px;
        }

        main {
            /* position: fixed; */
            margin-top: -30px;
        }

        footer {
            position: fixed;
            bottom: -20px;
            left: 0px;
            right: 0px;
            height: 40px;

            /** Extra personal styles **/
            /*background-color: #03a9f4;*/
            /*color: white;*/
            text-align: left;
            line-height: 40px;
            /* font-family: 'Segoe UI'; */
            font-size: 11px;
        }

        #footer .page:after {
            content: counter(page);
        }

        #title {
            font-weight: bold;
            text-align: center;
            text-decoration: underline;
            font-size: 14pt;
        }

        .text-center {
            text-align: center;
        }

        table.tb-header {
            width: 100%;
            /* border-collapse: separate;
                border-spacing: 1px; */
        }

        table.tb-header td {
            vertical-align: top !important;
        }

        table.tb-header td.full-border {
            border: 1pt solid;
            padding-left: 5px;
            margin-right: 5px;
        }

        table.tb-footer,
        table.tb-detail {
            width: 100%;
        }

        table.tb-detail th {
            border: 1pt solid;
            /* background-color: lightgray; */
        }

        table.tb-detail td {
            border: 1pt solid;
            vertical-align: top !important;
        }

        table th {
            text-align: center;
            vertical-align: middle !important;
        }

        tr.hide_all>td,
        td.hide_all {
            border-style: hidden;
            border: none !important;
        }

        .img_approval {
            max-width: 70%;
        }
    </style>
</head>

<body>
    <!-- <header>
        <?php echo $data->nama_pabrik; ?>
        <img src="data:image/png;base64, <?php echo base64_encode(file_get_contents(base_url() . 'assets/apps/img/logo-lg.png')); ?>" class="logo">
    </header> -->
    <main>
        <div style="border: 1px solid; padding: 10px 0;">
            <div id="title">DEKLARASI PERJALANAN DINAS</div>
        </div>
        <table class="tb-header" cellspacing="1" cellpadding="4px" style="border: 1px solid; margin: 5px 0;">
            <tr>
                <td style="width: 30%;">Nama</td>
                <td style="width: 5px;">:</td>
                <td class=""><?php echo $data['pengajuan_deklarasi']->nama_karyawan; ?></td>
            </tr>
            <tr>
                <td style="width: 30%;">NIK</td>
                <td style="width: 5px;">:</td>
                <td class=""><?php echo $data['pengajuan_deklarasi']->nik; ?></td>
            </tr>
            <tr>
                <td style="width: 30%;">Divisi/Bagian </td>
                <td style="width: 5px;">:</td>
                <td class=""><?php echo $data['personel']->nama_divisi; ?></td>
            </tr>
            <tr>
                <td style="width: 30%;">No. Surat Perjalanan Dinas</td>
                <td style="width: 5px;">:</td>
                <td class=""><?php echo $data['pengajuan_deklarasi']->no_trip; ?></td>
            </tr>
            <tr>
                <td style="width: 30%;">Factory Tujuan</td>
                <td style="width: 5px;">:</td>
                <td class=""><?php echo $data['pabrik_tujuan']; ?></td>
            </tr>
            <tr>
                <td style="width: 30%;">Kota Tujuan</td>
                <td style="width: 5px;">:</td>
                <td class=""><?php echo $data['kota_tujuan']; ?></td>
            </tr>
        </table>
        <!-- Table biaya -->
        <table width="100%" cellspacing="0" cellpadding="4px" class="tb-detail">
            <thead>
                <tr>
                    <th style="width:20%;">Tanggal</th>
                    <th style="width:25%;">Jenis</th>
                    <th>Keterangan</th>
                    <th style="width:20%;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($data['details_deklarasi_biaya'] as $biaya) {
                    echo '<tr>
                        <td>' . $biaya->tanggal_format . '</td>
                        <td>' . $biaya->tipe_expense_text . '</td>
                        <td>' . $biaya->keterangan . '</td>
                        <td>Rp. <span style="float: right;">' . number_format($biaya->jumlah, 2, ".", ",") . '</span></td>
                    </tr>';
                }
                ?>
                <tr>
                    <td colspan="3">Jumlah Biaya</td>
                    <td>Rp. <span style="float: right;"><?php echo number_format($data['pengajuan_deklarasi']->total_biaya, 2, ".", ",");?></span></td>
                </tr>
                <tr>
                    <td colspan="3">Uang Muka (bila ada)</td>
                    <td>Rp. <span style="float: right;"><?php echo number_format($data['pengajuan_deklarasi']->total_um, 2, ".", ",");?></span></td>
                </tr>
                <tr>
                    <td colspan="3">Potongan Uang Makan</td>
                    <td>Rp. <span style="float: right;">-</span></td>
                </tr>
                <tr>
                    <td colspan="3">Dibayarkan</td>
                    <td>Rp. <span style="float: right;"><?php echo number_format($data['pengajuan_deklarasi']->total_bayar, 2, ".", ",");?></span></td>
                </tr>
            </tbody>
        </table>
        <table width="100%" border="1" cellspacing="0" cellpadding="4px" style="table-layout: fixed; margin: 5px 0;">
            <tr>
                <td colspan="2" style="border-right: none;"></td>
                <td align="center" style="border-left: none;">Jakarta, <?php echo $data['pengajuan_deklarasi']->tanggal_buat_format; ?></td>
            </tr>
            <tr>
                <td align="center" style="border: none;">Diketahui oleh,</td>
                <td align="center" style="border: none;">Disetujui oleh,</td>
                <td align="center" style="border: none;">Diajukan oleh,</td>
            </tr>
            <tr>
                <td class="" style="height: 60px; border: none !important; text-align:center;">
                    <img src="data:image/png;base64, <?php echo base64_encode(file_get_contents(base_url() . 'assets/apps/img/disetujui.png')); ?>" class="img_approval">
                </td>
                <td class="" style="height: 60px; border: none !important; text-align:center;">
                    <img src="data:image/png;base64, <?php echo base64_encode(file_get_contents(base_url() . 'assets/apps/img/disetujui.png')); ?>" class="img_approval">
                </td>
                <td class="" style="height: 60px; border: none !important; text-align:center;">
                    <img src="data:image/png;base64, <?php echo base64_encode(file_get_contents(base_url() . 'assets/apps/img/disetujui.png')); ?>" class="img_approval">
                </td>
            </tr>
            <tr>
                <td class="text-center" style="border: none !important;">
                    <span style="text-decoration: underline;">
                        <?php
                        echo "( " . @$data['nama_approval']['atasan1'] . " )";
                        ?>
                    </span>
                    <br>
                    <span style="font-size: 7pt;">Atasan Langsung (minimal Manager)</span>
                </td>
                <td class="text-center" style="border: none !important;">
                    <span style="text-decoration: underline;">
                        <?php
                        echo "( " . @$data['nama_approval']['pejabat_berwenang'] . " )";
                        ?>
                    </span>
                    <br>
                    <span style="font-size: 7pt;">Manager Kantor / User Payroll / HRBP Dept. Head</span>
                </td>
                <td class="text-center" style="border: none !important;">
                    <span style="text-decoration: underline;">
                        <?php
                        echo "( " . $data['personel']->nama . " )";
                        ?>
                    </span>
                    <br>
                    <span style="font-size: 7pt;">Yang melakukan Perjalanan Dinas</span>
                </td>
            </tr>
        </table>
        <div style="border: 1px solid; padding: 2px 4px; font-size: 8pt; margin: 5px 0;">FRM-KM.CRP.31-03.02/1020 <span style="float: right;"><i>Manual (1) Rangkap : (1) Kasir</i></span></div>
    </main>
</body>

</html>