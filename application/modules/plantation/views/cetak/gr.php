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
        <div id="title">TANDA TERIMA GUDANG</div>
        <br>
        <table class="tb-header" cellspacing="1" cellpadding="4px">
            <tr>
                <td>NO TTG</td>
                <td style="width: 5px;">:</td>
                <td class="full-border" style="width: 35%;"><?php echo $data->no_gr; ?></td>
                <td style="padding-left: 10px;">TANGGAL</td>
                <td style="width: 5px;">:</td>
                <td class="full-border" style="width: 35%;"><?php echo $data->tanggal_format; ?></td>
            </tr>
            <tr>
                <td>VENDOR</td>
                <td style="width: 5px;">:</td>
                <td class="full-border"><?php echo $data->nama_vendor; ?></td>
                <td style="padding-left: 10px;">PO</td>
                <td style="width: 5px;">:</td>
                <td class="full-border"><?php echo $data->no_po; ?></td>
            </tr>
        </table>
        <br>
        <!-- Table row -->
        <table width="100%" cellspacing="0" cellpadding="4px" class="tb-detail">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 5%;">NO</th>
                    <th colspan="2">BARANG</th>
                    <th colspan="2">JUMLAH</th>
                    <th rowspan="2">Keterangan</th>
                </tr>
                <tr>
                    <th style="width: 15%;">KODE</th>
                    <th style="width: 25%;">NAMA BARANG</th>
                    <th>QTY</th>
                    <th>SAT</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($data->detail as $detail) {
                    echo '<tr>';
                    echo '  <td  align="center">' . $no . '</td>';
                    echo '  <td>' . $detail->kode_barang . '</td>';
                    echo '  <td>' . $detail->nama_barang . '</td>';
                    echo '  <td align="center">' . number_format($detail->jumlah, 2, '.', ',') . '</td>';
                    echo '  <td align="center">' . $detail->satuan . '</td>';
                    echo '  <td>' . $detail->keterangan . '</td>';
                    echo '</tr>';
                    $no++;
                }
                ?>
            </tbody>
        </table>
        <br><br>
        <table width="100%" border="1" cellspacing="0" cellpadding="4px">
            <tr>
                <td align="center">DIBUAT OLEH</td>
                <td align="center">DIPERIKSA OLEH</td>
                <td align="center">DIKETAHUI OLEH</td>
                <td align="center">DISERAHKAN OLEH</td>
            </tr>
            <tr>
                <td class="" style="height: 50px;"></td>
                <td class="" style="height: 50px;"></td>
                <td class="" style="height: 50px;"></td>
                <td class="" style="height: 50px;"></td>
            </tr>
            <tr>
                <td align="center">STAFF GUDANG</td>
                <td align="center">KTU</td>
                <td align="center">ESTATE MANAGER</td>
                <td align="center"></td>
            </tr>
        </table>
    </main>
</body>

</html>