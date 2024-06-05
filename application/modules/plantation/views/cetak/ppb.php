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
    </style>
</head>

<body>
    <header>
        <?php echo $data->nama_pabrik; ?>
        <img src="data:image/png;base64, <?php echo base64_encode(file_get_contents(base_url() . 'assets/apps/img/logo-lg.png')); ?>" class="logo">
    </header>
    <main>
        <div id="title">PERMOHONAN PEMBELIAN BARANG</div>
        <br>
        <table class="tb-header">
            <tr>
                <td>NOMOR PPB</td>
                <td style="width: 5px;">:</td>
                <td class="full-border" style="width: 35%;"><?php echo $data->no_ppb; ?></td>
                <td style="padding-left: 10px;">TANGGAL PPB</td>
                <td style="width: 5px;">:</td>
                <td class="full-border" style="width: 35%;"><?php echo date($data->tanggal_format); ?></td>
            </tr>
        </table>
        <br>
        <table width="100%" cellspacing="0" cellpadding="4px" class="tb-detail">
            <thead>
                <tr>
                    <th>Kode Barang</th>
                    <th style="width: 20%;">Nama Barang</th>
                    <th>Satuan</th>
                    <th>Tipe</th>
                    <th>Stok</th>
                    <th>Jumlah<br>Diminta</th>
                    <th>Jumlah<br>Disetujui</th>
                    <th>Referensi Harga</th>
                    <th>Spesifikasi/<br>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($data->detail as $detail) {
                    $tipe = "";
                    switch ($detail->classification) {
                        case 'A':
                            $tipe = "Asset";
                            break;
                        case 'K':
                            $tipe = "Expense";
                            break;
                        case 'I':
                            $tipe = "Inventory";
                            break;
                        default:
                            $tipe = "";
                            break;
                    }
                    echo '<tr>';
                    echo '  <td align="center">' . $detail->kode_barang . '</td>';
                    echo '  <td>' . $detail->nama_barang . '</td>';
                    echo '  <td align="center">' . $detail->satuan . '</td>';
                    echo '  <td >' . $tipe . '</td>';
                    echo '  <td align="center">' . number_format($detail->stok, 2, '.', ',') . '</td>';
                    echo '  <td align="center">' . number_format($detail->jumlah, 2, '.', ',') . '</td>';
                    echo '  <td align="center">' . number_format($detail->jumlah_disetujui, 2, '.', ',') . '</td>';
                    echo '  <td align="right">' . number_format($detail->harga, 2, ".", ",") . '</td>';
                    echo '  <td >' . $detail->keterangan . '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
        <br>
        <table width="100%" cellspacing="0" cellpadding="4px" class="tb-header">
            <tr>
                <td style="width: 10%;">Catatan :</td>
                <!-- <td>:</td> -->
                <td class="full-border" style="height: 50px;"><?php echo $data->perihal; ?></td>
            </tr>
        </table>
        <br>
        <table width="100%" border="1" cellspacing="0" cellpadding="4px">
            <tr>
                <td align="center">DIBUAT</td>
                <td align="center">DIPERIKSA</td>
                <td align="center" colspan="4">DISETUJUI</td>
            </tr>
            <tr>
                <td class="" style="height: 50px;"></td>
                <td class="" style="height: 50px;"></td>
                <td class="" style="height: 50px;"></td>
                <td class="" style="height: 50px;"></td>
                <td class="" style="height: 50px;"></td>
                <td class="" style="height: 50px;"></td>
            </tr>
            <tr>
                <td align="center" style="width: 16%;">Asst. Gudang/Kasie</td>
                <td align="center" style="width: 16%;">KTU</td>
                <td align="center" style="width: 17%;">Estate Manager</td>
                <td align="center" style="width: 17%;">QAD Dept Head</td>
                <td align="center" style="width: 17%;">Operational Dept Head</td>
                <td align="center" style="width: 17%;">Direktur</td>
            </tr>
            <tr>
                <td class="">Nama&Tgl.</td>
                <td class="">Nama&Tgl.</td>
                <td class="">Nama&Tgl.</td>
                <td class="">Nama&Tgl.</td>
                <td class="">Nama&Tgl.</td>
                <td class="">Nama&Tgl.</td>
            </tr>
        </table>
        <div style="font-size: 6pt;"><i><b>Lembar 1</b> untuk Gudang; <b>Lembar 2</b> untuk Peminta</i></div>
        <div style="font-size: 6pt;"><i>Nama, Jabatan, Paraf, dan Tandatangan harus jelas.</i></div>
    </main>
</body>

</html>