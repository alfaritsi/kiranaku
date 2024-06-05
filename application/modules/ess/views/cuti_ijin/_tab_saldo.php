<?php 
$sisa_saldo_cuti = 0;
foreach ($list_saldo as $saldo){
	$sisa_saldo_cuti += $saldo->jumlah - $saldo->terpakai - $saldo->pengajuan;
}
?>
<div class="badge bg-yellow text-uppercase margin-bottom">Saldo Total <?php echo $sisa_saldo_cuti ?> Hari</div>
<table class="table table-striped table-bordered table-responsive my-datatable-extends">
    <thead>
    <tr>
        <th>Jenis Cuti</th>
        <th>Saldo Awal</th>
        <th>Terpakai</th>
        <th>Pengajuan</th>
        <th>Sisa Saldo</th>
        <th>Tanggal Awal</th>
        <th>Tanggal Akhir</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($list_saldo as $saldo): ?>
        <tr>
            <td><?php echo $saldo->nama ?></td>
            <td><?php echo $saldo->jumlah ?> Hari</td>
            <td><?php echo $saldo->terpakai ?> Hari</td>
            <td><?php echo $saldo->pengajuan ?> Hari</td>
            <td><?php echo $saldo->jumlah - $saldo->terpakai - $saldo->pengajuan ?> Hari</td>
            <td><?php echo $this->generate->generateDateFormat($saldo->tanggal_awal) ?></td>
            <td><?php echo $this->generate->generateDateFormat($saldo->tanggal_akhir) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<div class="well well-sm">
    <ul class="list-unstyled">
        <li class="text-info"><strong><i class="fa fa-info-circle"></i>&nbsp;Keterangan :</strong></li>
        <li class="text-muted"><strong>Saldo Total :</strong> Jumlah saldo yang masih aktif</li>
        <li class="text-muted">
            <strong>Saldo Awal :</strong> Jumlah saldo cuti yang didapat berdasarkan ulang tahun masa kerja dan/atau
            hak cuti yang timbul atas aktifitas yang lain
        </li>
        <li class="text-muted"><strong>Terpakai :</strong> Jumlah cuti yang sudah disetujui HR</li>
        <li class="text-muted"><strong>Pengajuan :</strong> Jumlah cuti yang sudah diajukan karyawan dan disetujui atasan</li>
        <li class="text-muted"><strong>Sisa Saldo :</strong> Saldo Awal - Terpakai - Pengajuan</li>
    </ul>
</div>