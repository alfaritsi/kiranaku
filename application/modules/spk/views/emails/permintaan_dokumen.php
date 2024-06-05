<html>
<body>
<?php if (SPK_EMAIL_DEBUG_MODE): ?>
    <b>Original To :</b>&nbsp;<?php echo $emailOri ?><br/><br/>
<?php endif; ?>
<b>Kepada Bapak/Ibu,</b><br><br>
Berikut adalah pemberitahuan permohonan permintaan dokumen legal.<br><br>
<table width='300'>
    <tr>
        <td width='20'></td>
        <td width='90'>NIK</td>
        <td>: <?php echo $owner->nik ?></td>
    </tr>
    <tr>
        <td></td>
        <td>Nama</td>
        <td> : <?php echo $owner->nama ?></td>
    </tr>
    <tr>
        <td></td>
        <td>Nama Dokumen</td>
        <td>: <?php echo $permintaan->nama_permintaan_dok ?></td>
    </tr>
    <tr>
        <td></td>
        <td>Nomor Dokumen</td>
        <td>: <?php echo $permintaan->nomor_dokumen ?></td>
    </tr>
</table>
<br><br><br>
Harap Segera Ditindak Lanjuti<br>
<b>Kiranaku Auto-MailSystem</b>
</body>
</html>