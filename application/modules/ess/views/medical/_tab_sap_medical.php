<div class="row">
    <div class="col-md-12">
        <div class="btn-group btn-group-sm pull-right margin-bottom">
            <a href="<?php echo base_url('ess/medical/excel/sap/' . $lokasi . ($lokasi != 'ho' ? ($manager ? '/mg' : '/kasi') : '') . '?tanggal_awal=' . $tanggal_awal . '&tanggal_akhir=' . $tanggal_akhir) ?>"
               target="_blank" class="btn btn-sm btn-success">
                <i class="fa fa-download"></i> &nbsp;Export To SAP
            </a>
            <a href="javascript:void(0)" id="btn-sap-medical"
               class="btn btn-sm btn-warning btn-sap-medical">
                <i class="fa fa-random"></i> &nbsp Sinkronisasi Data Medical
            </a>
            <a href="javascript:void(0)" id="btn-sap-medical-nik"
               class="btn btn-sm btn-default btn-sap-medical-nik">
                <i class="fa fa-random"></i> &nbsp Sinkronisasi Data Medical by NIK
            </a>
            <form name="filter-medical-sap">
                <input hidden name="lokasi" id="lokasi" value="<?php echo $lokasi ?>"/>
                <input hidden name="nik" id="nik" value=""/>
            </form>
        </div>
    </div>
</div>
<table class="table table-bordered table-responsive my-datatable-extends-order table-striped"
       id="table-kelengkapan-menunggu"
       data-page-length='10' data-order='[[1,"desc"],[0,"desc"]]' data-length-change="false">
    <thead>
    <tr>
        <th>No. Pengajuan</th>
        <th>Tanggal</th>
        <th>NIK</th>
        <th>Nama</th>
        <th>Jenis Klaim</th>
        <!--        <th>Sisa Plafon Awal</th>-->
        <th>Total Klaim</th>
        <th>Detail Kwitansi</th>
        <th>Status</th>
        <th data-orderable="false"></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($list_medical as $data): ?>
        <tr>
            <td><?php echo $data->nomor ?></td>
            <td><?php echo $this->generate->generateDateFormat($data->tanggal_buat) ?></td>
            <td><?php echo $data->nik ?></td>
            <td><?php echo $data->nama_karyawan ?></td>
            <td><?php echo $this->less->jenis_fbk($data->kode) ?></td>
            <!--            <td>--><?php //echo $this->less->convert_rupiah(0) ?><!--</td>-->
            <td><?php echo $this->less->convert_rupiah($data->total_kwitansi) ?></td>
            <td>
                <a class="detail-kwitansi" href="javascript:void(0)" data-kwitansi='<?php echo $data->enId ?>'
                   data-disetujui="false">
                    <span class="badge bg-green"><?php echo count($data->kwitansi); ?> Kwitansi</span>
                </a>
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
                               data-history='<?php echo $data->enId ?>'><i class='fa fa-history'></i> History</a></li>
                    </ul>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>