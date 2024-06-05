<div class="row">
    <div class="col-md-12">
        <h4>
            &nbsp;
        </h4>
    </div>
</div>
<table class="table table-bordered table-responsive my-datatable-extends-order table-striped"
       id="table-kelengkapan-history"
       data-page-length='10' data-order='[[1,"desc"],[0,"desc"]]' data-length-change="false">
    <thead>
    <tr>
        <th data-orderable="false">No. Pengajuan</th>
        <th data-orderable="false">Tanggal</th>
        <th data-orderable="false">NIK</th>
        <th data-orderable="false">Nama</th>
        <th data-orderable="false">Jenis Klaim</th>
        <th data-orderable="false">Sisa Plafon Awal</th>
        <th data-orderable="false">Total Klaim</th>
        <th data-orderable="false">Detail Kwitansi</th>
        <th data-orderable="false">Detail Disetujui</th>
        <th data-orderable="false">Sisa Plafon Akhir</th>
        <th data-orderable="false">Status</th>
        <th data-orderable="false"></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($list_history as $data):
        $plafons = $this->less->get_plafon_sisa(
            array(
                'tanggal_akhir' => $data->tanggal_buat,
                'tahun' => date('Y',strtotime($data->tanggal_buat)),
                'id_before' => $data->id_fbk,
                'nik' => $data->nik
            )
        );

        switch ($data->kode) {
            case 'BRJL':
                $sisa_plafon = $plafons['sisa_fbk_jalan'];
                break;
            case 'BRIN':
                $sisa_plafon = '-';
                break;
            case 'BBNR':
                $sisa_plafon = $plafons['sisa_fbk_bersalin_normal'];
                break;
            case 'BBCS':
//                $sisa_plafon = $plafons['sisa_fbk_bersalin_cesar'];
                $sisa_plafon = '-';
                break;
            case 'BLNS':
                $sisa_plafon = $plafons['sisa_fbk_lensa'];
                break;
            case 'BBKI':
                $sisa_plafon = $plafons['sisa_fbk_frame'];
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
                if (in_array($data->kode,array('BRIN','BBNR','BBCS')))
                    echo '-';
                else
                {

                    echo $this->less->convert_rupiah($sisa_plafon + $data->total_ganti);
                }
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
                if (in_array($data->kode,array('BRIN','BBNR','BBCS')))
                    echo '-';
                else
                    echo $this->less->convert_rupiah($sisa_plafon);
                ?>
            </td>
            <td><span class="badge <?php echo $data->warna ?>"><?php echo $data->nama_status ?></span></td>
            <td>
                <div class='input-group-btn'>
                    <button type='button' class='btn btn-xs btn-default dropdown-toggle' data-toggle='dropdown'><span
                                class='fa fa-th-large'></span></button>
                    <ul class='dropdown-menu pull-right'>
                        <?php if ($data->id_fbk_status == 1): ?>
                            <li><a href='javascript:void(0)' class='edit' data-edit='<?php echo $data->enId ?>'><i
                                            class='fa fa-edit'></i> Edit</a></li>
                            <li><a href='javascript:void(0)' class='delete' data-delete='<?php echo $data->enId ?>'
                                   data-action='delete_na'><i class='fa fa-trash'></i> Delete</a></li>
                        <?php else: ?>
                            <li><a href='javascript:void(0)' class='detail-medical'
                                   data-estimasi="false"
                                   data-detail='<?php echo $data->enId ?>'><i
                                            class='fa fa-search'></i> Detail</a></li>
                            <li><a href='javascript:void(0)' class='history-medical'
                                   data-history='<?php echo $data->enId ?>'><i
                                            class='fa fa-history'></i> History</a></li>
                        <?php endif; ?>
                        <?php if ($data->id_fbk_status <> ESS_MEDICAL_STATUS_TDK_LENGKAP): ?>
                            <li class="divider" role="separator"></li>
                            <li><a href='javascript:void(0)' class='cetak-medical'
                                   data-cetak='<?php echo $data->enId ?>'><i
                                            class='fa fa-print'></i> Cetak</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>