<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered table-responsive my-datatable-extends-order table-striped" id="table-history-sapho"
               data-page-length='10' data-order='[[0,"desc"]]'>
            <thead>
            <tr>
                <th>Tanggal Pengajuan</th>
                <th>NIK</th>
                <th>Nama</th>
                <th>Form</th>
                <th>Tanggal Awal</th>
                <th>Tanggal Akhir</th>
                <th>Jumlah</th>
                <th>Catatan</th>
                <th data-orderable="false">By system</th>
                <th>Status</th>
                <th data-orderable="false"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($list_history as $data): ?>
                <tr>
                    <td><?php echo $this->generate->generateDateFormat($data->tanggal_buat) ?></td>
                    <td><?php echo $data->nik ?></td>
                    <td><?php echo $data->nama_karyawan ?></td>
                    <td><?php echo $data->form ?></td>
                    <td><?php echo $this->generate->generateDateFormat($data->tanggal_awal) ?></td>
                    <td><?php echo $this->generate->generateDateFormat($data->tanggal_akhir) ?></td>
                    <td><?php echo $data->jumlah ?> Hari</td>
                    <td><?php echo $data->alasan ?></td>
                    <td><?php echo $data->by_system ? '<i class="fa fa-check text-success"></i>':'' ?></td>
                    <td><span class="badge <?php echo $data->warna ?>"><?php echo $data->nama_status ?></span></td>
                    <td>
                        <div class='input-group-btn'>
                            <button type='button' class='btn btn-xs btn-default dropdown-toggle' data-toggle='dropdown'><span class='fa fa-th-large'></span></button>
                            <ul class='dropdown-menu pull-right'>
                                <li><a href='javascript:void(0)' class='detail' data-detail='<?php echo $data->enId ?>'><i class='fa fa-search'></i> Detail</a></li>
                                <li><a href='javascript:void(0)' class='history' data-history='<?php echo $data->enId ?>'><i class='fa fa-history'></i> History</a></li>
                                <?php if (in_array($data->id_cuti_status, array(ESS_CUTI_STATUS_DIBATALKAN))): ?>
                                    <li class="divider"></li>
                                    <li><a href='javascript:void(0)' class='bak-lampiran-batal' data-lampiran='<?php echo $data->enId ?>'><i
                                                    class='fa fa-image'></i> Lihat Lampiran</a></li>
                                <?php endif; ?>
                                <?php if (in_array($data->id_cuti_status, array(ESS_CUTI_STATUS_DISETUJUI_ATASAN, ESS_CUTI_STATUS_DISETUJUI_HR))): ?>
                                    <li class="divider"></li>
                                    <li><a href='javascript:void(0)' class='cutiijin-batal'
                                           data-batal='<?php echo $data->enId ?>'><i
                                                    class='fa fa-times'></i> Set pembatalan</a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>