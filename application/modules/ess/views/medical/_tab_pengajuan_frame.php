<?php
$tahunKlaim = date('Y');
if(isset($_GET['tahun_klaim']) && ESS_EMAIL_DEBUG_MODE)
    $tahunKlaim = $_GET['tahun_klaim'];

if($tahun == $tahunKlaim) : ?>
<div class="row">
    <?php if ($sisa_fbk_frame > 0): ?>
        <div class="col-md-3">
            <h4>
                <p class="badge bg-yellow">
                    <b><i class="fa fa-umbrella"></i> Sisa Plafon Frame
                        : <?php echo $this->less->convert_rupiah($sisa_fbk_frame); ?></b>
                </p>
            </h4>
        </div>
        <div class="col-md-offset-6 col-md-3">
            <?php if ($tahun == $tahunKlaim): ?>
                <div class="btn-group btn-group-sm pull-right">
                    <a href="javascript:void(0)" id="btn-add-pengajuan-frame"
                       class="btn btn-sm btn-success btn-add-pengajuan"
                       data-form="Frame">
                        <i class="fa fa-plus-square"></i> &nbsp Form Frame
                    </a>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="col-md-12">
            <div class="callout callout-warning">
                <p>
                    <i class="fa fa-warning"></i> <?php echo $warning ?>
                </p>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php endif; ?>
<table class="table table-bordered table-responsive my-datatable-extends-order table-striped" id="table-pengajuan-frame"
       data-page-length='10' data-order='[]' data-length-change="false">
    <thead>
    <tr>
        <th>No. Pengajuan</th>
        <th>Tanggal</th>
        <th>Total Klaim</th>
        <th>Detail Kwitansi</th>
        <th>Detail Disetujui</th>
        <th>Sisa Plafon</th>
        <th>Status</th>
        <th data-orderable="false"></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($list_frame as $data):

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
            <td><?php echo $this->less->convert_rupiah($data->total_kwitansi) ?></td>
            <td>
                <a class="detail-kwitansi" href="javascript:void(0)" data-kwitansi='<?php echo $data->enId ?>' data-disetujui="false">
                    <span class="badge bg-green"><?php echo count($data->kwitansi); ?> Kwitansi</span>
                </a>
            </td>
            <td><a class="detail-kwitansi-disetujui" href="javascript:void(0)" data-disetujui="true"
                   data-kwitansi='<?php echo $data->enId ?>'><span
                            class="badge bg-blue"><?php echo count($data->kwitansi_disetujui); ?> Disetujui</span></a>
            </td>
            <td>
                <?php
                if($data->id_fbk_status == ESS_MEDICAL_STATUS_DISETUJUI)
                    echo $this->less->convert_rupiah($data->plafon_frame-$data->total_ganti);
                else
                    echo $this->less->convert_rupiah($plafons['sisa_fbk_frame']);
                ?>
            </td>
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
                                   data-detail='<?php echo $data->enId ?>'><i
                                            class='fa fa-search'></i> Detail</a></li>
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
