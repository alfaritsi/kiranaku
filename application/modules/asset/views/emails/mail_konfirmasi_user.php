<html>
<body style=" background-color: #386d22; margin:0; font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;">
<center style="width: 100%;">
    <div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">

    </div>
    <div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
        Harap segera ditindak lanjuti, Terima kasih atas perhatiannya.
    </div>
    <div class="email-container" style="max-width: 500px; margin: 0 auto;">
        <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="500">
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td style="color: #fff; padding:20px;" align="center">
                    <h1 style="margin-bottom: 0;">Preventive Maintenance</h1>
                    <hr style="margin-top: 4px; margin-bottom: 4px; border: none; height: 2px; background: #ffffff;"/>
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
                                <?php if (PM_EMAIL_DEBUG_MODE): ?>
                                    <p><strong>Original To :</strong>&nbsp;<?php echo $emailOri ?></p>
                                <?php endif; ?>
                                <p><strong>Kepada Bapak/Ibu,</strong></p>
                                <p>Berikut adalah pemberitahuan dari PM KiranaKu</p>
                                <table role="presentation" border="0" width="100%" cellpadding="0" cellspacing="0">
                                    <tbody>
                                    <tr>
                                        <td>
                                            <strong>Konfirmasi Preventive Maintenance Aset</strong>
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
                                        <td width="30%" valign="top">
                                            <p>
                                                <strong>Nama Aset</strong>
                                            </p>
                                        </td>
                                        <td style="text-align: right;" valign="top">
                                            <?php echo join('<br/>', explode('||', $main->detail_aset)); ?>
                                        </td>
                                    </tr>
                                    <?php if (isset($main->pic)) : ?>
                                        <tr>
                                            <td width="30%" valign="top">
                                                <p>
                                                    <strong>PIC</strong>
                                                </p>
                                            </td>
                                            <td style="text-align: right;"
                                                valign="top"><?php echo $main->nama_pic; ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <td colspan="2" style="padding: 20px;" align="center">
                                            <a style="height: 100%; width: 100%; padding: 10px;background: #386d22; color: white; text-decoration: none; border-radius: 8px; box-shadow: 0px 2px 2px 2px #00000026;"
                                               href="<?php echo site_url('asset/maintenance/konfirmasi/' . $this->generate->kirana_encrypt($main->id_main)) ?>">
                                                KONFIRMASI DISINI
                                            </a>
                                        </td>
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
                    <small>Kiranaku Auto-MailSystem</small>
                    <br/>
                    <strong style="color: #214014; font-size: 10px;">Terkirim
                        pada <?php echo date('d.m.Y H:i:s'); ?></strong>
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