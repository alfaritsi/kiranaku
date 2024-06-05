<div class="row">
    <div class="col-md-12">
        <form name="filter-laporan-cuti" method="post">
            <div class="row">
                <div class="col-md-3 col-md-offset-5">
                    <div class="form-group">
                        <div class="input-group">
                            <label class="input-group-addon" for="id_plant">Status</label>
                            <select class="form-control select2" name="status" id="status"
                                    data-placeholder="Status">
                                <option>Semua</option>
                                <option value="0" <?php echo (isset($status) and $status == 0) ? 'selected' : ''; ?>>
                                    Pengajuan Terlambat/Pulang Cepat
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="input-group input-daterange" id="filter-date">
                            <label class="input-group-addon" for="tanggal-awal">Tanggal</label>
                            <input type="text" id="tanggal_awal" name="tanggal_awal"
                                   value="<?php echo $this->generate->generateDateFormat($tanggal_awal); ?>"
                                   class="form-control" autocomplete="off">
                            <label class="input-group-addon" for="tanggal-awal">-</label>
                            <input type="text" id="tanggal_akhir" name="tanggal_akhir"
                                   value="<?php echo $this->generate->generateDateFormat($tanggal_akhir); ?>"
                                   class="form-control" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<table class="table table-bordered table-responsive table-striped" id="table-bak-history"
       data-page-length='10' data-order='[[0,"desc"]]' data-length-change="false"
       data-search="<?php echo $searchNik ?>">
    <thead>
    <tr>
        <th>Tanggal Absen</th>
        <th>NIK</th>
        <th>Nama</th>
        <th>Absen Masuk</th>
        <th>Absen Keluar</th>
        <th>Tanggal Input</th>
        <th>Alasan</th>
        <th>Keterangan</th>
        <th>Status</th>
        <th data-visible="false" data-searchable="true"></th>
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
            <td><?php echo $data->keterangan ?></td>
            <td>
                <?php if (in_array($data->detail, array('MISS_CI', 'MISS_CO', 'MISS_CICO'))) : ?>
                    <i class='fa fa-check text-success'></i>
                <?php else: ?>
                    <span class="badge <?php echo $data->warna ?>"><?php echo $data->nama_status ?></span>
                <?php endif; ?>
            </td>
            <td><?php echo(in_array($data->detail, array('MISS_CI', 'MISS_CO', 'MISS_CICO')) ? $data->read_notif : '') ?></td>
            <td>
                <div class='input-group-btn'>
                    <button type='button' class='btn btn-xs btn-default dropdown-toggle' data-toggle='dropdown'><span
                                class='fa fa-th-large'></span></button>
                    <ul class='dropdown-menu pull-right'>
                        <li><a href='javascript:void(0)' class='bak-detail' data-detail='<?php echo $data->enId ?>'><i
                                        class='fa fa-search'></i> Detail</a></li>
                        <li><a href='javascript:void(0)' class='bak-history' data-history='<?php echo $data->enId ?>'><i
                                        class='fa fa-history'></i> History</a></li>
                        <?php if ($data->id_bak_status == ESS_BAK_STATUS_DISETUJUI) : ?>
                            <li class="divider"></li>
                            <li>
                                <a href='javascript:void(0)' class='approval' data-action="disapprove"
                                   data-catatan='<?php echo $data->enId ?>'
                                   data-data='<?php echo json_encode(array()) ?>'
                                >
                                    <i class='fa fa-times'></i> Batalkan
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>