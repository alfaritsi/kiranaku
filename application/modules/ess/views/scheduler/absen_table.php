<b>Delete request : </b><?php echo ($delete_request) ? 'Iya' : 'Tidak'; ?>
<br/>
<br/>
<table cellpadding="5" border="1" cellspacing="0">
    <thead>
    <tr>
        <th>NIK</th>
        <th>Tanggal</th>
        <th>Absen Masuk</th>
        <th>Absen Keluar</th>
        <th>DWS</th>
        <th>Absen Ke</th>
        <th>Jenis Absen</th>
        <th>Dupe</th>
        <th>Cek Next</th>
        <th>Saved</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($data as $d): ?>
        <tr>
            <td><?php echo $d['nik'] ?></td>
            <td><?php echo $d['tanggal'] ?></td>
            <td><?php echo $d['absen_masuk'] ?></td>
            <td><?php echo $d['absen_keluar'] ?></td>
            <td><?php echo $d['jadwal'] ?></td>
            <td><?php echo $d['absen_ke'] ?></td>
            <td><?php echo $d['tipe'] ?></td>
            <td><?php echo $d['bak_cek'] ?></td>
            <td><?php echo $d['cek_next'] ?></td>
            <td><?php echo $d['saved'] ? 'Ya' : 'Tidak' ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>