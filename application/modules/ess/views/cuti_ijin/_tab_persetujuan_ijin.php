<div class="row">
    <div class="col-md-3">
        <h4>Form Ijin</h4>
    </div>
</div>
<table class="table table-bordered table-responsive table-striped" id="table-persetujuan-ijin"
       data-page-length='10' data-order='[[0,"desc"]]' data-length-change="false" data-search="<?php echo $searchNik ?>">
    <thead>
    <tr>
        <th>Tanggal Pengajuan</th>
        <th>NIK</th>
        <th>Nama</th>
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
    <?php foreach ($list_data_ijin as $data): ?>
        <tr>
            <td><?php echo $this->generate->generateDateFormat($data->tanggal_buat) ?></td>
            <td><?php echo $data->nik ?></td>
            <td><?php echo $data->nama_karyawan ?></td>
            <td><?php echo $data->nama_jenis ?></td>
            <td><?php echo $this->generate->generateDateFormat($data->tanggal_awal) ?></td>
            <td><?php echo $this->generate->generateDateFormat($data->tanggal_akhir) ?></td>
            <td><?php echo $data->jumlah ?> Hari</td>
            <td><?php echo $data->alasan ?></td>
            <td><span class="badge <?php echo $data->warna ?>"><?php echo $data->nama_status ?></span></td>
            <td>
                <div class='input-group-btn'>
                    <button type='button' class='btn btn-xs btn-default dropdown-toggle' data-toggle='dropdown'><span
                                class='fa fa-th-large'></span></button>
                    <ul class='dropdown-menu pull-right'>
                        <li><a href='javascript:void(0)' class='detail-persetujuan'
                               data-detail='<?php echo $data->enId ?>'
                            >
                                <i class='fa fa-search'></i> Detail</a>
                        </li>
                        <?php if(isset($data->gambar)) : ?>
                            <li><a href='<?php echo $data->gambar; ?>'><i class='fa fa-image'></i> Lihat lampiran</a></li>
                        <?php endif; ?>
                        <li><a href='javascript:void(0)' class='approval text-success'
                               data-action="approve"
                               data-catatan="<?php echo $data->enId ?>"
                               data-data='<?php echo json_encode(array("person"=>$data->nik." ".$data->nama_karyawan,"jenis"=>$data->form,"tanggal"=>$this->generate->generateDateFormat($data->tanggal_awal)." - ".$this->generate->generateDateFormat($data->tanggal_akhir)))?>'>
                                <i class='fa fa-check'></i> Disetujui</a>
                        </li>
                        <li><a href='javascript:void(0)' class='approval text-danger'
                               data-action="disapprove"
                               data-catatan="<?php echo $data->enId ?>"
                               data-data='<?php echo json_encode(array("person"=>$data->nik." ".$data->nama_karyawan,"jenis"=>$data->form,"tanggal"=>$this->generate->generateDateFormat($data->tanggal_awal)." - ".$this->generate->generateDateFormat($data->tanggal_akhir)))?>'>
                                <i class='fa fa-times'></i> Ditolak</a>
                        </li>
                    </ul>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>