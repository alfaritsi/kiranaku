<html>
<body>
<b>Kepada Bapak/Ibu,</b><br><br>
Berikut adalah pemberitahuan dari Kiranaku.<br><br>
<b><u>Konfirmasi Pengajuan Cuti/ Ijin</u></b><br><br><br>
<table width='300'>
    <tr>
        <td width='20'></td>
        <td width='90'>NIK</td>
        <td>: <?php echo $data['data']->nik ?></td>
    </tr>
    <tr>
        <td></td>
        <td>Nama</td>
        <td>: <?php echo $data['data']->nama_karyawan ?></td>
    </tr>
    <tr>
        <td></td>
        <td>Form</td>
        <td>: <?php echo $data['data']->form ?></td>
    </tr>
    <tr>
        <td></td>
        <td>Tanggal Pengajuan</td>
        <td>: <?php echo $this->generate->generateDateFormat($data['data']->tanggal_buat) ?></td>
    </tr>
    <tr>
        <td></td>
        <td>Tanggal Awal</td>
        <td>: <?php echo $this->generate->generateDateFormat($data['data']->tanggal_awal) ?></td>
    </tr>
    <tr>
        <td></td>
        <td>Tanggal Akhir</td>
        <td>: <?php echo $this->generate->generateDateFormat($data['data']->tanggal_akhir) ?></td>
    </tr>
    <?php if ($data['data']->form == 'Cuti') : ?>
        <tr>
            <td></td>
            <td>Saldo saat ini</td>
            <td>: <?php echo($data['sisa_cuti']['sisa'] + $data['data']->jumlah) ?> Hari</td>
        </tr>
    <?php else: ?>
        <tr>
            <td></td>
            <td>Jenis Ijin</td>
            <td>: <?php echo $data['data']->nama_jenis ?></td>
        </tr>
    <?php endif; ?>
    <tr>
        <td></td>
        <td>Jumlah Hari</td>
        <td>: <?php echo $data['data']->jumlah ?> Hari</td>
    </tr>
    <tr>
        <td></td>
        <td>Alasan</td>
        <td>: <?php echo $data['data']->alasan ?></td>
    </tr>
    <?php if ($data['data']->id_cuti_status == ESS_CUTI_STATUS_DITOLAK) : ?>
        <tr>
            <td></td>
            <td>Catatan</td>
            <td>: <?php echo $data['data']->catatan ?></td>
        </tr>
    <?php endif; ?>
    <tr>
        <td></td>
        <td>Status Cuti/Ijin</td>
        <td>: <?php echo $data['data']->nama_status ?></td>
    </tr>
</table>
<br><br><br>
Harap Segera Ditindak Lanjuti,<br>
<b>Kiranaku Auto-MailSystem</b>
</body>
</html>