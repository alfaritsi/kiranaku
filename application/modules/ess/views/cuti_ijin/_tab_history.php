<form name="filter-history" method="post">
    <div class="row">
        <div class="col-md-offset-4 col-md-2">
            <div class="form-group">
                <div class="input-group">
                    <label class="input-group-addon" for="form_filter">Form</label>
                    <select class="select2 form-control" id="form_filter" name="form">
                        <option <?php if($filter['form']=='Semua') echo 'selected'; ?> value="Semua">Semua</option>
                        <option <?php if($filter['form']=='Cuti') echo 'selected'; ?> value="Cuti">Cuti</option>
                        <option <?php if($filter['form']=='Ijin') echo 'selected'; ?> value="Ijin">Ijin</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <div class="input-group">
                    <label class="input-group-addon">Status</label>
                    <select class="select2 form-control" id="id_cuti_status_filter" name="id_cuti_status">
                        <option <?php if($filter['id_cuti_status'] == 'Semua') echo 'selected'; ?> value="Semua">Semua</option>
                        <?php foreach ($cuti_status as $status) : ?>
                            <option <?php if(isset($filter['id_cuti_status']) && $filter['id_cuti_status'] == $status->id_cuti_status) echo 'selected'; ?> value="<?php echo $status->id_cuti_status?>"><?php echo $status->nama; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <div class="input-group input-daterange" id="filter-date">
                    <label class="input-group-addon" for="tanggal-awal_filter">Tanggal</label>
                    <input type="text" id="tanggal_awal_filter" name="tanggal_awal" value="<?php echo $this->generate->generateDateFormat($tanggal_awal);?>" class="form-control" autocomplete="off">
                    <label class="input-group-addon" for="tanggal-awal_filter">-</label>
                    <input type="text" id="tanggal_akhir_filter" name="tanggal_akhir" value="<?php echo $this->generate->generateDateFormat($tanggal_akhir);?>" class="form-control" autocomplete="off">
                </div>
            </div>
        </div>
    </div>
</form>
<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered table-responsive my-datatable-extends-order table-striped" id="table-history"
               data-page-length='10' data-order='[]'>
            <thead>
            <tr>
                <th>Tanggal Pengajuan</th>
                <th>Form</th>
                <th>Tanggal Awal</th>
                <th>Tanggal Akhir</th>
                <th>Jumlah</th>
                <th>Catatan</th>
                <th>Status</th>
                <th data-orderable="false"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($list_history as $data): ?>
                <tr>
                    <td><?php echo $this->generate->generateDateFormat($data->tanggal_buat) ?></td>
                    <td><?php echo $data->form ?></td>
                    <td><?php echo $this->generate->generateDateFormat($data->tanggal_awal) ?></td>
                    <td><?php echo $this->generate->generateDateFormat($data->tanggal_akhir) ?></td>
                    <td><?php echo $data->jumlah ?> Hari</td>
                    <td><?php echo $data->alasan ?></td>
                    <td><span class="badge <?php echo $data->warna ?>"><?php echo $data->nama_status ?></span></td>
                    <td>
                        <div class='input-group-btn'>
                            <button type='button' class='btn btn-xs btn-default dropdown-toggle' data-toggle='dropdown'><span class='fa fa-th-large'></span></button>
                            <ul class='dropdown-menu pull-right'>
                                <li><a href='javascript:void(0)' class='detail' data-detail='<?php echo $data->enId ?>'><i class='fa fa-search'></i> Detail</a></li>
                                <li><a href='javascript:void(0)' class='history' data-history='<?php echo $data->enId ?>'><i class='fa fa-history'></i> History</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>