<table class="table table-bordered table-responsive table-striped" id="table-bak-menunggu"
       data-page-length='10' data-order='[[1,"desc"]]' data-length-change="false">
    <thead>
    <tr>
        <th></th>
        <th>Tanggal Absen</th>
        <th>NIK</th>
        <th>Nama</th>
        <th>Absen Masuk</th>
        <th>Absen Keluar</th>
        <th>Tanggal Input</th>
        <th>Alasan</th>
        <th>Status</th>
        <th data-orderable="false"></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($list_menunggu as $data): ?>
        <tr>
            <td><?php echo $data->enId ?></td>
            <td><?php echo $this->generate->generateDateFormat($data->tanggal_absen) ?></td>
            <td><?php echo $data->nik ?></td>
            <td><?php echo $data->nama_karyawan ?></td>
            <td><?php echo $data->absen_masuk ?></td>
            <td><?php echo $data->absen_keluar ?></td>
            <td><?php echo $this->generate->generateDateFormat($data->tanggal_buat) ?></td>
            <td><?php echo $data->alasan ?></td>
            <td>
                <span class="badge <?php echo $data->warna ?>"><?php echo $data->nama_status ?></span></td>
            <td>
                <div class='input-group-btn'>
                    <button type='button' class='btn btn-xs btn-default dropdown-toggle' data-toggle='dropdown'><span class='fa fa-th-large'></span></button>
                    <ul class='dropdown-menu pull-right'>
                        <li><a href='javascript:void(0)' class='bak-detail-persetujuan' data-detail='<?php echo $data->enId ?>'><i class='fa fa-search'></i> Detail</a></li>
                        <li><a href='javascript:void(0)' class='bak-history' data-history='<?php echo $data->enId ?>'><i class='fa fa-history'></i> History</a></li>
                    </ul>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<div class="row">
    <div class="form-group">
        <div class="col-md-12 text-center">
            <button class="btn btn-sm btn-danger" id="btn-disapprove" name="btn-disapprove">Ditolak</button>
            <button class="btn btn-sm btn-success" id="btn-approve" name="btn-approve">Disetujui</button>
        </div>
    </div>
</div>