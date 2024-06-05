<html>
<body>
<?php if (SPK_EMAIL_DEBUG_MODE): ?>
    <b>Original To :</b>&nbsp;<?php echo $emailOri ?><br/><br/>
<?php endif; ?>
<b>Kepada Bapak/Ibu,</b><br><br>
Berikut adalah pemberitahuan permohonan pengembalian dokumen legal.<br><br>
<table width='400'>
    <tr>
        <td width='20'></td>
        <td width='120'>NIK</td>
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
        <td>: <?php echo $pengembalian->nama_pengembalian_dok ?></td>
    </tr>
    <tr>
        <td></td>
        <td>Nomor Dokumen</td>
        <td>: <?php echo $pengembalian->nomor_dokumen ?></td>
    </tr>
</table>
<br><br>
Harap Segera Ditindak Lanjuti<br>
<b>Kiranaku Auto-MailSystem</b>
</body>
</html>