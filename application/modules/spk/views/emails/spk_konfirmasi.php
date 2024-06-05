<html>
<body>
<div class='text'>
    <?php if (SPK_EMAIL_DEBUG_MODE): ?>
        <b>Original To :</b>&nbsp;<?php echo $emailOri ?><br/><br/>
    <?php endif; ?>
    <b>Kepada Bapak/Ibu,</b><br><br>
    Berikut adalah permohonan pembuatan SPK dari :<br><br>
    <table width='300'>
        <tr>
            <td></td>
            <td>Nama</td>
            <td> : <?php echo $owner->nama; ?></td>
        </tr>
        <tr>
            <td width='20'></td>
            <td width='90'>NIK</td>
            <td>: <?php echo $owner->nik; ?></td>
        </tr>
        <tr>
            <td></td>
            <td>Pabrik</td>
            <td>: <?php echo $spk->plant; ?></td>
        </tr>
        <tr>
            <td></td>
            <td>Jenis SPK</td>
            <td>: <?php echo $spk->jenis_spk; ?></td>
        </tr>
        <tr>
            <td></td>
            <td>Nama SPK</td>
            <td>: <?php echo $spk->nama_spk; ?></td>
        </tr>
        <tr>
            <td></td>
            <td>Jenis Vendor</td>
            <td>: <?php echo $spk->jenis_vendor; ?></td>
        </tr>
        <tr>
            <td></td>
            <td>Nama Vendor</td>
            <td>: <?php echo $spk->nama_vendor; ?></td>
        </tr>
    </table>
    <br><br><br>
    Harap Segera Ditindak Lanjuti<br>
    <b>Kiranaku Auto-MailSystem</b>
</div>
</body>
</html>