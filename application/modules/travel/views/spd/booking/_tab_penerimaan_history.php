<table class="table table-bordered table-responsive my-datatable-extends-order table-striped" id="table-history-penerimaan"
       data-page-length='10' data-order='[]' data-length-change="false">
    <thead>
    <tr>
        <th>Tanggal Berangkat</th>
        <th>Tanggal Kembali</th>
        <th>No Trip</th>
        <th>NIK</th>
        <th>Nama</th>
        <th>Aktifitas</th>
        <th>Tujuan</th>
        <th>Penginapan</th>
        <th>Transportasi</th>
        <th>Status</th>
        <th data-orderable="false"></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($list as $d): ?>
        <tr>
            <td><?php echo $d->tanggal_berangkat ?></td>
            <td><?php echo $d->tanggal_kembali ?></td>
            <td><?php echo $d->no_trip ?></td>
            <td><?php echo $d->nik ?></td>
            <td><?php echo $d->nama_karyawan ?></td>
            <td><?php echo $d->activity_label ?></td>
            <td><?php echo $d->tujuan_lengkap ?></td>
            <td><?php echo $d->jenis_penginapan ?></td>
            <td><?php echo implode(', ', $d->transportasi_label) ?></td>
            <td><?php echo $d->booking_status ?></td>
            <td>
                <div class='input-group-btn'>
                    <button type='button' class='btn btn-xs btn-default dropdown-toggle'
                            data-toggle='dropdown'><span class='fa fa-th-large'></span>
                    </button>
                    <ul class='dropdown-menu pull-right'>
                        <?php echo $d->actions; ?>
                    </ul>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>