<div class="row">
    <div class="col-md-12">
        <h4>
            &nbsp;
        </h4>
    </div>
</div>
<table class="table table-bordered table-responsive my-datatable-extends-order table-striped" id="table-kelengkapan-menunggu"
       data-page-length='10' data-order='[[1,"desc"],[0,"desc"]]' data-length-change="false">
    <thead>
    <tr>
        <th>No. Pengajuan</th>
        <th>Tanggal</th>
        <th>NIK</th>
        <th>Nama Karyawan</th>
        <th>Nama Pasien</th>
        <th>Jenis Klaim</th>
<!--        <th>Sisa Plafon Awal</th>-->
        <th>Total Klaim</th>
        <th>Detail Kwitansi</th>
        <th>Status</th>
        <th data-orderable="false"></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($list_menunggu as $data): ?>
        <tr>
            <td><?php echo $data->nomor ?></td>
            <td><?php echo $this->generate->generateDateFormat($data->tanggal_buat) ?></td>
            <td><?php echo $data->nik ?></td>
            <td><?php echo $data->nama_karyawan ?></td>
            <td><?php echo $data->nama_pasien ?></td>
            <td><?php echo $this->less->jenis_fbk($data->kode) ?></td>
<!--            <td>--><?php //echo $this->less->convert_rupiah(0) ?><!--</td>-->
            <td><?php echo $this->less->convert_rupiah($data->total_kwitansi) ?></td>
            <td>
                <a class="detail-kwitansi" href="javascript:void(0)" data-kwitansi='<?php echo $data->enId ?>' data-disetujui="false">
                    <span class="badge bg-green"><?php echo count($data->kwitansi); ?> Kwitansi</span>
                </a>
            </td>
            <td><span class="badge <?php echo $data->warna ?>"><?php echo $data->nama_status ?></span></td>
            <td>
                <div class='input-group-btn'>
                    <button type='button' class='btn btn-xs btn-default dropdown-toggle' data-toggle='dropdown'><span class='fa fa-th-large'></span></button>
                    <ul class='dropdown-menu pull-right'>
                        <?php if($data->id_fbk_status == 1):?>
                            <li><a href='javascript:void(0)' class='lengkap' data-lengkap='<?php echo $data->enId ?>'><i class='fa fa-check'></i> Set Kelengkapan</a></li>
                            <li><a href='javascript:void(0)' class='lampiran' data-lampiran='<?php echo $data->enId ?>'><i class='fa fa-image'></i> Lihat lampiran</a></li>
                        <?php else: ?>
                            <li><a href='javascript:void(0)' class='detail-medical' data-estimasi="false" data-detail='<?php echo $data->enId ?>'><i class='fa fa-search'></i> Detail</a></li>
                            <li><a href='javascript:void(0)' class='history-medical' data-history='<?php echo $data->enId ?>'><i class='fa fa-history'></i> History</a></li>
                        <?php endif; ?>
                        <?php if ($data->id_fbk_status <> ESS_MEDICAL_STATUS_TDK_LENGKAP): ?>
                            <li class="divider" role="separator"></li>
                            <li><a href='javascript:void(0)' class='cetak-medical' data-cetak='<?php echo $data->enId ?>'><i
                                            class='fa fa-print'></i> Cetak</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>