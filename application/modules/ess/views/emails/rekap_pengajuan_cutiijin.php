<html>
<body style=" background-color: #386d22; margin:0; font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;">
<center style="width: 100%;">
    <div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
        Harap segera ditindak lanjuti. Rekapan Konfirmasi Pengajuan Cuti/Ijin yang belum dilakukan.
    </div>
    <div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
        &zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
    </div>
    <div class="email-container" style="max-width: 800px; margin: 0 auto;">
        <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
               style="min-width:600px;">
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
                                <?php if (ESS_EMAIL_SCHEDULER_DEBUG_MODE): ?>
                                    <p><b>Original To :</b>&nbsp;<?php echo $data['emailOri'] ?><br/></p>
                                <?php endif; ?>
                                <p><strong>Kepada Bapak/Ibu,</strong></p>
                                <p>Berikut adalah pemberitahuan dari ESS KiranaKu</p>
                                <table role="presentation" border="0" width="100%" cellpadding="0" cellspacing="0">
                                    <tbody>
                                    <tr>
                                        <td>
                                            <strong>Konfirmasi Pengajuan Cuti/Ijin</strong>
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
                                       cellpadding="4" style="font-size: 11px; border: 1px solid rgb(164,243,162); ">
                                    <tr>
                                        <th align="left" style="background-color: rgb(164,243,162);">NIK</th>
                                        <th align="left" style="background-color: rgb(164,243,162);">Nama</th>
                                        <th align="left" style="background-color: rgb(164,243,162);">Form</th>
                                        <th align="left" style="background-color: rgb(164,243,162);">Sisa Saldo</th>
                                        <th align="left" style="background-color: rgb(164,243,162);">Tanggal Awal</th>
                                        <th align="left" style="background-color: rgb(164,243,162);">Tanggal Akhir</th>
                                        <th align="left" style="background-color: rgb(164,243,162);">Jumlah Hari</th>
                                        <th align="left" style="background-color: rgb(164,243,162);">Alasan</th>
                                        <th align="left" style="background-color: rgb(164,243,162);">Status Pengajuan</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($data['list_menunggu'] as $data): ?>
                                        <tr>
                                            <td style="border-bottom: 1px solid rgb(164,243,162)"><?php echo $data->nik ?></td>
                                            <td style="border-bottom: 1px solid rgb(164,243,162)"><?php echo $data->nama_karyawan ?></td>
                                            <td style="border-bottom: 1px solid rgb(164,243,162)"><?php echo $data->form ?></td>
                                            <td style="border-bottom: 1px solid rgb(164,243,162)"><?php echo $data->sisa ?></td>
                                            <td style="border-bottom: 1px solid rgb(164,243,162)"><?php echo $this->generate->generateDateFormat($data->tanggal_awal) ?></td>
                                            <td style="border-bottom: 1px solid rgb(164,243,162)"><?php echo $this->generate->generateDateFormat($data->tanggal_akhir) ?></td>
                                            <td style="border-bottom: 1px solid rgb(164,243,162)"><?php echo $data->jumlah ?></td>
                                            <td style="border-bottom: 1px solid rgb(164,243,162)"><?php echo $data->alasan ?></td>
                                            <td style="border-bottom: 1px solid rgb(164,243,162)"><?php echo $data->nama_status ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr></tr>
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