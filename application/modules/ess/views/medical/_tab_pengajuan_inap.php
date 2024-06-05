<div class="row">
    <?php if($plafon_fbk_inap == 0) : ?>
        <div class="col-md-12 margin-bottom">
            <div class="callout callout-warning">
                <p>
                    <i class="fa fa-warning"></i> Anda belum mendapatkan bantuan kesehatan Rawat inap.
                </p>
            </div>
        </div>
    <?php else: ?>
        <div class="col-md-6">
            <h4>
                <p class="badge bg-yellow">
                    <b><i class="fa fa-umbrella"></i> Plafon Kamar / Hari
                        : <?php echo $this->less->convert_rupiah($plafon_fbk_inap); ?></b>
                </p>
            </h4>
        </div>
        <div class="col-md-offset-3 col-md-3 margin-bottom">
            <?php if($tahun == date('Y')): ?>
                <div class="btn-group btn-group-sm pull-right">
                    <a href="javascript:void(0)" id="btn-add-pengajuan-jalan"
                       class="btn btn-sm btn-success btn-add-pengajuan"
                       data-form="Rawat Inap">
                        <i class="fa fa-plus-square"></i> &nbsp Form Rawat Inap
                    </a>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
<table class="table table-bordered table-responsive my-datatable-extends-order table-striped" id="table-pengajuan-inap"
       data-page-length='10' data-order='[]' data-length-change="false">
    <thead>
    <tr>
        <th>No. Pengajuan</th>
        <th>Tanggal</th>
        <th>Jenis Sakit</th>
        <th>Total Klaim</th>
        <th>Detail Kwitansi</th>
        <th>Detail Disetujui</th>
        <!--        <th>Sisa Plafon</th>-->
        <th>Status</th>
        <th data-orderable="false"></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($list_inap as $data):

        $plafons = $this->less->get_plafon_sisa(
            array(
                'tanggal_akhir' => $data->tanggal_buat,
                'tahun' => date('Y',strtotime($data->tanggal_buat)),
                'id_before' => $data->id_fbk,
                'nik' => $data->nik
            )
        );?>
        <tr>
            <td><?php echo $data->nomor ?></td>
            <td><?php echo $this->generate->generateDateFormat($data->tanggal_buat) ?></td>
            <td><?php echo $data->sakit ?></td>
            <td><?php echo $this->less->convert_rupiah($data->total_kwitansi) ?></td>
            <td>
                <a class="detail-kwitansi" href="javascript:void(0)" data-kwitansi='<?php echo $data->enId ?>' data-disetujui="false">
                    <span class="badge bg-green"><?php echo count($data->kwitansi); ?> Kwitansi</span>
                </a>
            </td>
            <td><a class="detail-kwitansi-disetujui" href="javascript:void(0)" data-disetujui="true"
                   data-kwitansi='<?php echo $data->enId ?>'><span class="badge bg-blue"><?php echo count($data->kwitansi_disetujui); ?> Disetujui</span></a></td>
            <!--            <td>-</td>-->
            <td><span class="badge <?php echo $data->warna ?>"><?php echo $data->nama_status ?></span></td>
            <td>
                <div class='input-group-btn'>
                    <button type='button' class='btn btn-xs btn-default dropdown-toggle' data-toggle='dropdown'><span
                                class='fa fa-th-large'></span></button>
                    <ul class='dropdown-menu pull-right'>
                        <?php if (in_array($data->id_fbk_status, array(ESS_MEDICAL_STATUS_MENUNGGU, ESS_MEDICAL_STATUS_TDK_LENGKAP))): ?>
                            <li><a href='javascript:void(0)' class='edit' data-edit='<?php echo $data->enId ?>'><i
                                            class='fa fa-edit'></i> Edit</a></li>
                            <li><a href='javascript:void(0)' class='delete' data-delete='<?php echo $data->enId ?>'
                                   data-action='delete_na'><i class='fa fa-trash'></i> Delete</a></li>
                        <?php else: ?>
                            <li><a href='javascript:void(0)' class='detail-medical'
                                   data-detail='<?php echo $data->enId ?>'><i class='fa fa-search'></i> Detail</a></li>
                            <li><a href='javascript:void(0)' class='history-medical'
                                   data-history='<?php echo $data->enId ?>'><i class='fa fa-history'></i> History</a>
                            </li>
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