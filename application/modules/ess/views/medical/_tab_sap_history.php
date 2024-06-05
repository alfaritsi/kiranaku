<table class="table table-bordered table-responsive my-datatable-extends-order table-striped"
       id="table-kelengkapan-history"
       data-page-length='10' data-order='[[1,"desc"],[0,"desc"]]' data-length-change="false">
    <thead>
    <tr>
        <th>No. Pengajuan</th>
        <th>Tanggal</th>
        <th>NIK</th>
        <th>Nama</th>
        <th>Jenis Klaim</th>
        <th>Sisa Plafon Awal</th>
        <th>Total Klaim</th>
        <th>Detail Kwitansi</th>
        <th>Detail Disetujui</th>
        <th>Sisa Plafon Akhir</th>
        <th>Status</th>
        <th data-orderable="false"></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($list_history as $data):
        switch ($data->kode) {
            case 'BRJL':
                $sisa_plafon = $data->plafon_medical;
                break;
            case 'BRIN':
                $sisa_plafon = '-';
                break;
            case 'BBNR':
                $sisa_plafon = $data->plafon_persalinan;
                break;
            case 'BBCS':
                $sisa_plafon = $data->plafon_persalinan;
                break;
            case 'BLNS':
                $sisa_plafon = $data->plafon_lensa;
                break;
            case 'BBKI':
                $sisa_plafon = $data->plafon_frame;
                break;
        }
        ?>
        <tr>
            <td><?php echo $data->nomor ?></td>
            <td><?php echo $this->generate->generateDateFormat($data->tanggal_buat) ?></td>
            <td><?php echo $data->nik ?></td>
            <td><?php echo $data->nama_karyawan ?></td>
            <td><?php echo $this->less->jenis_fbk($data->kode) ?></td>
            <td>
                <?php
                if ($data->kode == 'BRIN')
                    echo '-';
                else
                    echo $this->less->convert_rupiah($sisa_plafon)
                ?>
            </td>
            <td><?php echo $this->less->convert_rupiah($data->total_kwitansi) ?></td>
            <td>
                <a class="detail-kwitansi" href="javascript:void(0)" data-kwitansi='<?php echo $data->enId ?>' data-disetujui="false">
                    <span class="badge bg-green"><?php echo count($data->kwitansi); ?> Kwitansi</span>
                </a>
            </td>
            <td>
                <a class="detail-kwitansi" href="javascript:void(0)" data-kwitansi='<?php echo $data->enId ?>' data-disetujui="true">
                    <span class="badge bg-blue"><?php echo count($data->kwitansi_disetujui); ?> Kwitansi</span>
                </a>
            </td>
            <td>
                <?php
                if ($data->kode == 'BRIN')
                    echo '-';
                else
                    echo $this->less->convert_rupiah($sisa_plafon - $data->total_ganti)
                ?>
            </td>
            <td><span class="badge <?php echo $data->warna ?>"><?php echo $data->nama_status ?></span></td>
            <td>
                <div class='input-group-btn'>
                    <button type='button' class='btn btn-xs btn-default dropdown-toggle' data-toggle='dropdown'><span
                                class='fa fa-th-large'></span></button>
                    <ul class='dropdown-menu pull-right'>
                        <li><a href='javascript:void(0)' class='detail-medical' data-estimasi="false" data-detail='<?php echo $data->enId ?>'><i
                                        class='fa fa-search'></i> Detail</a></li>
                        <li><a href='javascript:void(0)' class='history-medical'
                               data-history='<?php echo $data->enId ?>'><i
                                        class='fa fa-history'></i> History</a></li>
                    </ul>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>