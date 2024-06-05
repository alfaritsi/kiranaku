<div class="row">
    <div class="col-md-3">
        <h4>Form Ijin</h4>
    </div>
    <div class="col-md-offset-6 col-md-3">
        <div class="btn-group btn-group-sm pull-right">
            <a href="javascript:void(0)" class="btn btn-sm btn-success btn-add-pengajuan" data-form="Ijin" data-saldo='<?php echo json_encode($sisa_cuti) ?>'><i class="fa fa-plus-square"></i> &nbsp;Tambah Ijin</a>
        </div>
    </div>
</div>
<table class="table table-bordered table-responsive my-datatable-extends-order table-striped" id="table-pengajuan-ijin"
       data-page-length='10' data-order='[]' data-length-change="false">
    <thead>
    <tr>
        <th>Tanggal Pengajuan</th>
        <th>Jenis ijin</th>
        <th>Tanggal Awal</th>
        <th>Tanggal Akhir</th>
        <th>Jumlah</th>
        <th>Catatan</th>
        <th>Status</th>
        <th data-orderable="false"></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($list_pengajuan_ijin as $data): ?>
        <tr>
            <td><?php echo $this->generate->generateDateFormat($data->tanggal_buat) ?></td>
            <td><?php echo $data->nama_jenis ?></td>
            <td><?php echo $this->generate->generateDateFormat($data->tanggal_awal) ?></td>
            <td><?php echo $this->generate->generateDateFormat($data->tanggal_akhir) ?></td>
            <td><?php echo $data->jumlah ?> Hari</td>
            <td><?php echo $data->alasan ?></td>
            <td><span class="badge <?php echo $data->warna ?>"><?php echo $data->nama_status ?></span></td>
            <td>
                <div class='input-group-btn'>
                    <button type='button' class='btn btn-xs btn-default dropdown-toggle' data-toggle='dropdown'><span class='fa fa-th-large'></span></button>
                    <ul class='dropdown-menu pull-right'>
                        <?php if($data->by_system) : ?>
                            <li><a href="javascript:void(0)"><i class="fa fa-info-circle"></i>&nbsp;Pengajuan by System</a></li>
                            <li class="divider"></li>
                        <?php endif; ?>
                        <?php if($data->id_cuti_status == 1 && !$data->by_system):?>
                            <li><a href='javascript:void(0)' class='edit' data-edit='<?php echo $data->enId ?>'><i class='fa fa-edit'></i> Edit</a></li>
                            <li><a href='javascript:void(0)' class='delete' data-delete='<?php echo $data->enId ?>' data-action='delete_na'><i class='fa fa-trash'></i> Delete</a></li>
                        <?php else: ?>
                            <li><a href='javascript:void(0)' class='detail' data-detail='<?php echo $data->enId ?>'><i class='fa fa-search'></i> Detail</a></li>
                            <li><a href='javascript:void(0)' class='history' data-history='<?php echo $data->enId ?>'><i class='fa fa-history'></i> History</a></li>
                        <?php endif; ?>
                        <?php if(isset($data->gambar)) : ?>
                            <li><a href='<?php echo $data->gambar; ?>'><i class='fa fa-image'></i> Lihat lampiran</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<div class="well well-sm">
    <ul class="list-unstyled">
        <li class="text-info"><strong><i class="fa fa-info-circle"></i>&nbsp;Keterangan :</strong></li>
        <li class="text-muted"><strong>Jumlah :</strong> Jumlah hari pengajuan ijin</li>
        <li class="text-muted"><strong>Catatan :</strong> Catatan pengajuan</li>
        <li class="text-muted"><strong>Status :</strong> Status pengajuan</li>
    </ul>
</div>