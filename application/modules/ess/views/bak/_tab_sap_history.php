<table class="table table-bordered table-responsive my-datatable-extends-order table-striped"
       id="table-kelengkapan-history"
       data-page-length='10' data-order='[[1,"desc"],[0,"desc"]]' data-length-change="false">
    <thead>
    <tr>
        <th>Tanggal absen</th>
        <th>NIK</th>
        <th>Nama</th>
        <th>Absen masuk</th>
        <th>Absen keluar</th>
        <th>Tanggal pengajuan</th>
        <th>Alasan</th>
        <th>Status</th>
        <th data-orderable="false"></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($list_history as $data): ?>
        <tr>
            <td><?php echo $this->generate->generateDateFormat($data->tanggal_absen) ?></td>
            <td><?php echo $data->nik ?></td>
            <td><?php echo $data->nama_karyawan ?></td>
            <td><?php echo $data->absen_masuk ?></td>
            <td><?php echo $data->absen_keluar ?></td>
            <td><?php echo $this->generate->generateDateFormat($data->tanggal_buat) ?></td>
            <td><?php echo $data->alasan ?></td>
            <td><span class="badge <?php echo $data->warna ?>"><?php echo $data->nama_status ?></span></td>
            <td>
                <div class='input-group-btn'>
                    <button type='button' class='btn btn-xs btn-default dropdown-toggle' data-toggle='dropdown'><span class='fa fa-th-large'></span></button>
                    <ul class='dropdown-menu pull-right'>
                        <li><a href='javascript:void(0)' class='bak-detail' data-detail='<?php echo $data->enId ?>'><i class='fa fa-search'></i> Detail</a></li>
                        <li><a href='javascript:void(0)' class='bak-history' data-history='<?php echo $data->enId ?>'><i class='fa fa-history'></i> History</a></li>
                        <?php if (in_array($data->id_bak_status, array(ESS_BAK_STATUS_DIBATALKAN))): ?>
                            <li class="divider"></li>
                            <li><a href='javascript:void(0)' class='bak-lampiran-batal' data-lampiran='<?php echo $data->enId ?>'><i
                                            class='fa fa-image'></i> Lihat Lampiran</a></li>
                        <?php endif; ?>
                        <?php if (in_array($data->id_bak_status, array(ESS_BAK_STATUS_DISETUJUI, ESS_BAK_STATUS_DISETUJUI_OLEH_HR, ESS_BAK_STATUS_COMPLETE))): ?>
                            <li class="divider"></li>
                            <li><a href='javascript:void(0)' class='bak-batal' data-batal='<?php echo $data->enId ?>'><i
                                            class='fa fa-times'></i> Set pembatalan</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>