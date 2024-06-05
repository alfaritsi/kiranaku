<?php
$color = "#AAAAAA";
switch ($data['data']->warna) {
    case "bg-green":
        $color = "#008d4c";
        break;
    case "bg-blue":
        $color = "#357ca5";
        break;
    case "bg-red":
        $color = "#d33724";
        break;
}
?>
<html>
<body style=" background-color: #386d22; margin:0; font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;">
<center style="width: 100%;">
    <div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
        Pengajuan Berita Acara Kehadiran oleh <?php echo $data['data']->nama_karyawan ?>.
    </div>
    <div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
        &zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
    </div>
    <div class="email-container" style="max-width: 600px; margin: 0 auto;">
        <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="600">
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td style="color: #fff; padding:20px;" align="center">
                    <h1 style="margin-bottom: 0;">Employee Self Service</h1>
                    <hr style="border-color: #ffffff; margin-bottom: 4px; margin-top: 4px;"/>
                    <h3 style="margin-top: 0;">KiranaKu</h3>
                </td>
            </tr>
            <tr>
                <td>
                    <table style="background-color: #ffffff; margin: auto; -webkit-box-shadow: 0px 2px 8px 0px rgba(0,0,0,0.4); -moz-box-shadow: 0px 2px 8px 0px rgba(0,0,0,0.4); box-shadow: 0px 2px 8px 0px rgba(0,0,0,0.4);"
                           role="presentation" border="0" width="100%" cellspacing="0"
                           cellpadding="0"
                           align="center">
                        <tbody>
                        <tr>
                            <td style="padding: 20px;">
                                <p><strong>Kepada Bapak/Ibu,</strong></p>
                                <p>Berikut adalah pemberitahuan dari ESS KiranaKu</p>
                                <table role="presentation" border="0" width="100%" cellpadding="0" cellspacing="0">
                                    <tbody>
                                    <tr>
                                        <td>
                                            <strong>Konfirmasi Pengajuan Berita Acara Kehadiran</strong>
                                        </td>
                                        <td width="100px" align="center" style="padding: 6px;
                                                background-color: <?php echo $color; ?>;
                                                color: #fff;
                                                border-radius: 8px;
                                                font-weight: bold;
                                                font-size: 12px;
                                                text-transform: uppercase">
                                            <?php echo $data['data']->nama_status ?>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td align="left"
                                style="background-color: #ffffff; padding: 20px; border-top: 1px dashed #386d22;">
                                <table role="presentation" border="0" width="100%" cellspacing="0"
                                       cellpadding="4" align="center">
                                    <tbody>
                                    <tr>
                                        <td width="30%">
                                            <p>
                                                <strong>NIK</strong>
                                            </p>
                                        </td>
                                        <td style="text-align: right;"><?php echo $data['data']->nik ?></td>
                                    </tr>
                                    <tr>
                                        <td width="30%">
                                            <p>
                                                <strong>Nama</strong>
                                            </p>
                                        </td>
                                        <td style="text-align: right;"><?php echo $data['data']->nama_karyawan ?></td>
                                    </tr>
                                    <tr>
                                        <td width="30%">
                                            <p>
                                                <strong>Tanggal Pengajuan</strong>
                                            </p>
                                        </td>
                                        <td style="text-align: right;"><?php echo $this->generate->generateDateFormat($data['data']->tanggal_buat) ?></td>
                                    </tr>
                                    <tr>
                                        <td width="30%">
                                            <p>
                                                <strong>Tanggal Absen</strong>
                                            </p>
                                        </td>
                                        <td style="text-align: right;"><?php echo $this->generate->generateDateFormat($data['data']->tanggal_absen) ?></td>
                                    </tr>
                                    <tr>
                                        <td width="30%">
                                            <p>
                                                <strong>Jam</strong>
                                            </p>
                                        </td>
                                        <td style="text-align: right;"><?php echo $data['data']->absen_masuk.' - '.$data['data']->absen_keluar ?></td>
                                    </tr>
                                    <tr>
                                        <td width="30%">
                                            <p>
                                                <strong>Alasan</strong>
                                            </p>
                                        </td>
                                        <td style="text-align: right;"><?php echo $data['data']->alasan ?></td>
                                    </tr>
                                    <tr>
                                        <td width="30%">
                                            <p>
                                                <strong>Keterangan</strong>
                                            </p>
                                        </td>
                                        <td style="text-align: right;"><?php echo $data['data']->keterangan ?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td align="left"
                                style="background-color: #ffffff; padding: 20px; border-top: 1px dashed #386d22;">
                                <p>
                                    Harap segera ditindak lanjuti,<br/>
                                    Terima kasih atas perhatiannya.
                                </p>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="color: #fff; padding-top:20px;" align="center">
                    <small>Kiranaku Auto-MailSystem</small><br/>
                    <strong style="color: #214014; font-size: 10px;">Terkirim pada <?php echo date('d.m.Y H:i:s');?></strong>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
        </table>
    </div>
</center>
</body>
</html>