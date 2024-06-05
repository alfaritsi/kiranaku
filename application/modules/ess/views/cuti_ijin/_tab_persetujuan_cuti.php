<form id="form-persetujuan-cuti">
    <input type="hidden" id="cuti-disetujui" name="cuti-disetujui">
    <div class="row">
        <div class="col-md-3">
            <h4>Form Cuti</h4>
        </div>
    </div>
    <table class="table table-bordered table-responsive table-striped" id="table-persetujuan-cuti"
           data-page-length='10' data-length-change="false" data-search="<?php echo $searchNik ?>">
        <thead>
        <tr>
            <th></th>
            <th>Tanggal Pengajuan</th>
            <th>NIK</th>
            <th>Nama</th>
            <th>Form</th>
            <th>Tanggal Awal</th>
            <th>Tanggal Akhir</th>
            <th>Jumlah</th>
            <th>Sisa Saldo</th>
            <th width="20">Catatan Atasan</th>
            <th>Status</th>
            <th data-orderable="false"></th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($list_data_cuti as $data):
            $sisa_cuti_temp = $this->less->get_cuti_sisa(
                array(null, $data->tanggal_akhir),
                $data->nik
            );
            $sisa_cuti_temp = $sisa_cuti_temp['sisa'];
            ?>
            <tr>
                <td><?php echo $data->enId ?></td>
                <td><?php echo $this->generate->generateDateFormat($data->tanggal_buat) ?></td>
                <td><?php echo $data->nik ?></td>
                <td><?php echo $data->nama_karyawan ?></td>
                <td><?php echo $data->form ?></td>
                <td><?php echo $this->generate->generateDateFormat($data->tanggal_awal) ?></td>
                <td><?php echo $this->generate->generateDateFormat($data->tanggal_akhir) ?></td>
                <td><?php echo $data->jumlah ?> Hari</td>
                <td><?php echo $sisa_cuti_temp ?> Hari</td>
                <td>
                    <a href="javascript:void(0);" class="btn btn-xs btn-default catatan"
                       data-catatan="<?php echo $data->enId ?>"
                       data-data='<?php echo json_encode(array("person" => $data->nik . " " . $data->nama_karyawan, "jenis" => $data->form, "tanggal" => $this->generate->generateDateFormat($data->tanggal_awal) . " - " . $this->generate->generateDateFormat($data->tanggal_akhir))) ?>'
                    >
                        <i class="fa fa-pencil"></i>
                        &nbsp;<span></span>
                    </a>
                </td>
                <td><span class="badge <?php echo $data->warna ?>"><?php echo $data->nama_status ?></span></td>
                <td>
                    <div class='input-group-btn'>
                        <a href='javascript:void(0)' class='btn btn-xs btn-default detail-persetujuan'
                           data-detail='<?php echo $data->enId ?>'
                           data-saldo=true
                        >
                            <i class='fa fa-search'></i></a>
                        </ul>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div class="row">
        <div class="form-group">
            <div class="col-md-12 text-center">
                <button class="btn btn-sm btn-danger" id="btn-disapprove" name="btn-disapprove">Ditolak</button>
                <button class="btn btn-sm btn-success" id="btn-approve" name="btn-approve">Disetujui</button>
            </div>
        </div>
    </div>
</form>