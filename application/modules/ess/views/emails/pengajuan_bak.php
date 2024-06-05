<html>
<body>
<b>Kepada Bapak/Ibu,</b><br><br>
Berikut adalah pemberitahuan dari Kiranaku.<br><br>
<b><u>Konfirmasi Pengajuan Berita Acara Kehadiran</u></b><br><br><br>
<table width='600'>
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
        <td>Tanggal Pengajuan</td>
        <td>: <?php echo $this->generate->generateDateFormat($data['data']->tanggal_buat) ?></td>
    </tr>
    <tr>
        <td></td>
        <td>Tanggal</td>
        <td>: <?php echo $this->generate->generateDateFormat($data['data']->tanggal_absen) ?></td>
    </tr>
    <tr>
        <td></td>
        <td>Jam</td>
        <td>: <?php echo $data['data']->absen_masuk.' - '.$data['data']->absen_keluar ?></td>
    </tr>
    <tr>
        <td></td>
        <td>Alasan</td>
        <td>: <?php echo $data['data']->alasan ?></td>
    </tr>
    <tr>
        <td></td>
        <td>Keterangan</td>
        <td>: <?php echo $data['data']->keterangan ?></td>
    </tr>
    <tr>
        <td></td>
        <td>Status Pengajuan</td>
        <td>: <?php echo $data['data']->nama_status ?></td>
    </tr>
</table>
<br><br><br>
Harap Segera Ditindak Lanjuti,<br>
<b>Kiranaku Auto-MailSystem</b>
</body>
</html>