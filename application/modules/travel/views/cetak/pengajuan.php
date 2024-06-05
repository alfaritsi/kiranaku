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
            background-color: lightgray;
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
        <div style="border: 1px solid; padding: 2px 0;">
            <div id="title">SURAT PERJALANAN DINAS</div>
            <div class="text-center" style="font-size: 11pt;">No. <?php echo $data['pengajuan']->no_trip; ?></div>
        </div>
        <table class="tb-header" cellspacing="1" cellpadding="4px" style="border: 1px solid; margin: 5px 0;">
            <tr>
                <td colspan="3">Yang melakukan perjalanan dinas,</td>
            </tr>
            <tr>
                <td style="width: 45%;">Nama (Sesuai KTP) / NIK</td>
                <td style="width: 5px;">:</td>
                <td class=""><?php echo $data['pengajuan']->nama_karyawan . " / " . $data['pengajuan']->nik; ?></td>
            </tr>
            <tr>
                <td style="width: 45%;">Nomor Hand Phone (for airlines confirmation)</td>
                <td style="width: 5px;">:</td>
                <td class=""><?php echo $data['pengajuan']->no_hp; ?></td>
            </tr>
            <tr>
                <td style="width: 45%;">Divisi/Jabatan </td>
                <td style="width: 5px;">:</td>
                <td class=""><?php echo $data['personel']->nama_divisi . " / " . $data['personel']->nama_departemen; ?></td>
            </tr>
            <tr>
                <td style="width: 45%;">Jenis Perjalanan Dinas</td>
                <td style="width: 5px;">:</td>
                <td class=""><?php echo $data['pengajuan']->jenis_tujuan; ?></td>
            </tr>
            <tr>
                <td style="width: 45%;">Kota / Factory / Estate Tujuan</td>
                <td style="width: 5px;">:</td>
                <td class=""><?php echo $data['tujuan_dinas']; ?></td>
            </tr>
            <tr>
                <td style="width: 45%;">Tanggal berangkat</td>
                <td style="width: 5px;">:</td>
                <td class=""><?php echo $data['pengajuan']->tanggal_berangkat; ?></td>
            </tr>
            <tr>
                <td style="width: 45%;">Tanggal kembali</td>
                <td style="width: 5px;">:</td>
                <td class=""><?php echo $data['pengajuan']->tanggal_kembali; ?></td>
            </tr>
            <tr>
                <td style="width: 45%;">Transportasi</td>
                <td style="width: 5px;">:</td>
                <td class="">
                    <?php
                    $transport = "";
                    if (!empty($data['pengajuan']->transportasi_label))
                        $transport = implode(", ", $data['pengajuan']->transportasi_label);
                    echo $transport;
                    ?>
                </td>
            </tr>
            <tr>
                <td style="width: 45%;">Penginapan </td>
                <td style="width: 5px;">:</td>
                <td class=""><?php echo ucwords(implode(", ", $data['jenis_penginapan'])); ?></td>
            </tr>
            <tr>
                <td style="width: 45%;">Uang Muka (bila ada)</td>
                <td style="width: 5px;"></td>
                <td class=""></td>
            </tr>
            <tr>
                <td style="width: 45%;">&nbsp;&nbsp;&nbsp;&nbsp;Akomodasi Transportasi</td>
                <td style="width: 5px;">:</td>
                <td class="">Rp. <?php echo $data['uangmuka']['transport']; ?></td>
            </tr>
            <tr>
                <td style="width: 45%;">&nbsp;&nbsp;&nbsp;&nbsp;Hotel</td>
                <td style="width: 5px;">:</td>
                <td class="">Rp. <?php echo $data['uangmuka']['hotel']; ?></td>
            </tr>
            <tr>
                <td style="width: 45%;">&nbsp;&nbsp;&nbsp;&nbsp;Uang Makan</td>
                <td style="width: 5px;">:</td>
                <td class="">Rp. <?php echo $data['uangmuka']['uang_makan']; ?></td>
            </tr>
            <tr>
                <td style="width: 45%;">&nbsp;&nbsp;&nbsp;&nbsp;Uang Saku</td>
                <td style="width: 5px;">:</td>
                <td class="">Rp. <?php echo $data['uangmuka']['uang_saku']; ?></td>
            </tr>
        </table>
        <table width="100%" border="1" cellspacing="0" cellpadding="4px" style="table-layout: fixed; margin: 5px 0;">
            <tr>
                <td colspan="3">Jakarta, <?php echo $data['pengajuan']->tanggal_buat_format; ?></td>
            </tr>
            <tr>
                <td align="center">Mengetahui,</td>
                <td align="center">Menyetujui *),</td>
                <td align="center">Yang Menugaskan,</td>
            </tr>
            <tr>
                <td class="" style="height: 60px; border-bottom: none !important; text-align:center;">
                    <img src="data:image/png;base64, <?php echo base64_encode(file_get_contents(base_url() . 'assets/apps/img/disetujui.png')); ?>" class="img_approval">
                </td>
                <td class="" style="height: 60px; border-bottom: none !important; text-align:center;">
                    <?php    
                        if ($data['personel']->ho == 'n' && $data['nama_approval']['atasan2']) {
                    ?>
                        <img src="data:image/png;base64, <?php echo base64_encode(file_get_contents(base_url() . 'assets/apps/img/disetujui.png')); ?>" class="img_approval">
                    <?php } ?>
                </td>
                <td class="" style="height: 60px; border-bottom: none !important; text-align:center;">
                    <img src="data:image/png;base64, <?php echo base64_encode(file_get_contents(base_url() . 'assets/apps/img/disetujui.png')); ?>" class="img_approval">
                </td>
            </tr>
            <tr>
                <td class="text-center" style="border-top: none !important;">
                    <?php
                        if ($data['nama_approval']) {
                            echo "( " . @$data['nama_approval']['pejabat_berwenang'] . " )";
                        }
                    ?>
                </td>
                <td class="text-center" style="border-top: none !important;">
                    <?php
                        $atasan2 = "(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)";
                        if ($data['personel']->ho == 'n') {
                            $atasan2 = "( " . @$data['nama_approval']['atasan2'] . " )";
                        }
                        echo $atasan2;
                    ?>
                </td>
                <td class="text-center" style="border-top: none !important;">
                    <?php
                        echo "( " . @$data['nama_approval']['atasan1'] . " )";
                    ?>
                </td>
            </tr>
            <tr>
                <td align="center" style="font-size: 6pt;">HROGA Dept. Head / Manager Kantor di Pabrik</td>
                <td align="center" style="font-size: 6pt;">Direktur Operasional / CEO Region</td>
                <td align="center" style="font-size: 6pt;">Atasan (minimal Division Head - HO / minimal Manager di Pabrik)</td>
            </tr>
        </table>
        <!-- Table aktifitas -->
        <div class="text-center" style="border: 1px solid; padding: 2px 0; font-size: 12pt; margin: 5px 0;">RENCANA AKTIVITAS</div>
        <table width="100%" cellspacing="0" cellpadding="4px" class="tb-detail">
            <thead>
                <tr>
                    <th style="width:20%;">Tanggal</th>
                    <th style="width:20%;">Pabrik/Kota</th>
                    <th>Aktivitas</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($data['rencana_aktifitas'] as $aktifitas) {
                    echo '<tr>
                        <td>' . $aktifitas->tanggal_aktifitas_format . '</td>
                        <td>' . $aktifitas->lokasi . '</td>
                        <td>' . $aktifitas->aktifitas . '</td>
                    </tr>';
                }
                ?>
            </tbody>
        </table>
        <div class="text-center" style="border: 1px solid; padding: 2px 0; font-size: 8pt; margin: 5px 0; background-color: lightgray;"><i>Diisi oleh HROGA</i></div>
        <table class="tb-header" cellspacing="1" cellpadding="4px" style="border: 1px solid; margin: 5px 0;">
            <tr>
                <td style="width: 25%;">No. Penerbangan Berangkat</td>
                <td style="width: 5px;">:</td>
                <td class=""><?php echo $data['tiket_berangkat']; ?></td>
            </tr>
            <tr>
                <td style="width: 25%;">No. Penerbangan Kembali</td>
                <td style="width: 5px;">:</td>
                <td class=""><?php echo $data['tiket_kembali']; ?></td>
            </tr>
        </table>
        <div style="border: 1px solid; padding: 2px 4px; font-size: 8pt; margin: 5px 0;">FRM-KM.CRP.31-01.02/1020 <span style="float: right;"><i>Manual 1 (satu) Rangkap : (1) Finance - HO / Kasir</i></span></div>
    </main>
</body>

</html>