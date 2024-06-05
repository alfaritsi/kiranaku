<div class="row">
    <div class="col-md-12">
        <div class="btn-group btn-group-sm pull-right margin-bottom">
            <a href="<?php echo base_url('ess/bak/excel/sap/' . $lokasi . ($lokasi != 'ho' ? ($manager ? '/mg' : '/kasi') : '') . '?tanggal_awal=' . $tanggal_awal . '&tanggal_akhir=' . $tanggal_akhir) ?>"
               target="_blank" class="btn btn-sm btn-success"><i class="fa fa-download"></i> &nbsp;Export To SAP</a>
            <a href="javascript:void(0)" id="btn-sap-<?php echo $lokasi ?>"
               class="btn btn-sm btn-warning btn-sap-bak">
                <i class="fa fa-random"></i> &nbsp Sinkronisasi Data BAK
            </a>
            <a href="javascript:void(0)" id="btn-sap-bak-nik"
               class="btn btn-sm btn-default">
                <i class="fa fa-random"></i> &nbsp Sinkronisasi Data BAK by NIK
            </a>
            <form name="bak-sap">
                <input hidden name="mode" value="json">
                <input hidden name="lokasi" id="lokasi" value="<?php echo $lokasi ?>"/>
                <input hidden name="nik" id="nik" value=""/>
                <input hidden name="tanggal_awal" id="sap-tanggal-awal"
                       value="<?php echo $this->generate->generateDateFormat($tanggal_awal); ?>"/>
                <input hidden name="tanggal_akhir" id="sap-tanggal-akhir"
                       value="<?php echo $this->generate->generateDateFormat($tanggal_akhir); ?>"/>
            </form>
        </div>
    </div>
</div>
<form name="filter-bak-sap" method="post">
    <input type="hidden" name="lokasi" value="<?php echo $lokasi ?>"/>
    <div class="row">
        <div class="col-md-offset-6 col-md-2">
            <div class="form-group">
                <div class="input-group">
                    <label class="input-group-addon">Status</label>
                    <select class="select2 form-control" id="id_bak_status_filter"
                            name="id_bak_status">
                        <option <?php if ($filter['id_bak_status'] == 'Semua') echo 'selected'; ?>
                                value="Semua">Semua
                        </option>
                        <?php foreach ($statuses as $status) : ?>
                            <option <?php if (isset($filter['id_bak_status']) && $filter['id_bak_status'] == $status->id_bak_status) echo 'selected'; ?>
                                    value="<?php echo $status->id_bak_status ?>"><?php echo $status->nama; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <div class="input-group input-daterange" id="filter-date">
                    <label class="input-group-addon" for="tanggal-awal_filter">Tanggal</label>
                    <input type="text" id="tanggal_awal_filter" name="tanggal_awal"
                           value="<?php echo $this->generate->generateDateFormat($tanggal_awal); ?>"
                           class="form-control" autocomplete="off">
                    <label class="input-group-addon" for="tanggal-awal_filter">-</label>
                    <input type="text" id="tanggal_akhir_filter" name="tanggal_akhir"
                           value="<?php echo $this->generate->generateDateFormat($tanggal_akhir); ?>"
                           class="form-control" autocomplete="off">
                </div>
            </div>
        </div>
    </div>
</form>
<table class="table table-bordered table-responsive my-datatable-extends-order table-striped"
       id="table-kelengkapan-menunggu"
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
    <?php foreach ($list_bak as $data): ?>
        <tr>
            <td><?php echo $this->generate->generateDateFormat($data->tanggal_absen) ?></td>
            <td><?php echo $data->nik ?></td>
            <td><?php echo $data->nama_karyawan ?></td>
            <td><?php echo $data->absen_masuk ?></td>
            <td><?php echo $data->absen_keluar ?></td>
            <td><?php echo $this->generate->generateDateFormat($data->tanggal_buat) ?></td>
            <td><?php echo $data->alasan ?></td>
            <td>
                <?php if (isset($data->warna)) : ?>
                    <span class="badge <?php echo $data->warna ?>"><?php echo $data->nama_status ?></span>
                <?php else: ?>
                    Hadir
                <?php endif; ?>
            </td>
            <td>
                <div class='input-group-btn'>
                    <button type='button' class='btn btn-xs btn-default dropdown-toggle' data-toggle='dropdown'><span
                                class='fa fa-th-large'></span></button>
                    <ul class='dropdown-menu pull-right'>
                        <li><a href='javascript:void(0)' class='bak-detail' data-detail='<?php echo $data->enId ?>'><i
                                        class='fa fa-search'></i> Detail</a></li>
                        <li><a href='javascript:void(0)' class='bak-history' data-history='<?php echo $data->enId ?>'><i
                                        class='fa fa-history'></i> History</a></li>
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