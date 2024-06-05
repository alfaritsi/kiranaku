<form name="filter-history" method="post">
    <div class="row">
        <div class="col-md-4 col-md-offset-8">
            <div class="form-group">
                <div class="input-group input-daterange" id="filter-date">
                    <label class="input-group-addon" for="tanggal-awal_filter">Tanggal</label>
                    <input type="text" id="tanggal_awal_filter" name="tanggal_awal" class="form-control" autocomplete="off" value="<?php echo $this->generate->generateDateFormat($tanggal_awal);?>">
                    <label class="input-group-addon" for="tanggal-awal_filter">-</label>
                    <input type="text" id="tanggal_akhir_filter" name="tanggal_akhir" class="form-control" autocomplete="off" value="<?php echo $this->generate->generateDateFormat($tanggal_akhir);?>">
                </div>
            </div>
        </div>
    </div>
</form>
<table class="table table-bordered table-responsive table-striped" id="table-history"
       data-page-length='10' data-order='[]' data-length-change="false">
    <thead>
    <tr>
        <th>Jenis History</th>
        <th>No Trip</th>
        <th>Tanggal Berangkat</th>
        <th>Tanggal Kembali</th>
        <th>Aktifitas</th>
        <th>Tujuan</th>
        <th>Keperluan</th>
        <th>Uang Muka</th>
        <th>Tanggal Update</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($list_history as $d): ?>
            <tr>
                <td>
                    <?php 
                        $label = "";
                        if($d->jenis_history == "Pengajuan"){
                            $label = "<span class='badge bg-green'>Pengajuan</span>";
                        } else if($d->jenis_history == "Pembatalan"){
                            $label = "<span class='badge bg-red'>Pembatalan</span>";
                        } else if($d->jenis_history == "Deklarasi"){
                            $label = "<span class='badge bg-blue'>Deklarasi</span>";
                        }
                        // $label = $d->jenis_history;
                        echo $label;
                    ?>
                    
                </td>
                <td><?php echo $d->no_trip ?></td>
                <td><?php echo $d->tanggal_berangkat ?></td>
                <td><?php echo $d->tanggal_kembali ?></td>
                <td><?php echo $d->activity_label ?></td>
                <td><?php echo $d->tujuan_lengkap ?></td>
                <td><?php echo $d->keperluan ?></td>
                <td><?php echo $this->generate->convert_rupiah($d->totalUM) ?></td>

                <td>
                    <?php 
                        $time_migrasi       = $this->generate->generateDateTimeFormat($d->tanggal_migrasi);
                        
                        // $time               = "";
                        /*if($d->jenis_history == "Pengajuan"){
                            $time = "<span class='badge bg-green'>".$time_migrasi."</span>";
                        } else if($d->jenis_history == "Pembatalan"){
                            $time = "<span class='badge bg-red'>".$time_migrasi."</span>";
                        } else if($d->jenis_history == "Deklarasi"){
                            $time = "<span class='badge bg-blue'>".$time_migrasi."</span>";
                        }*/
                        
                        echo $time_migrasi; 
                    ?>
                        
                </td>
                <td>
                    <div class='input-group-btn'>
                        <button type='button' class='btn btn-xs btn-default dropdown-toggle'
                                data-toggle='dropdown'><span class='fa fa-th-large'></span>
                        </button>
                        <ul class='dropdown-menu pull-right'>
                            <?php echo join('', $d->actions); ?>
                        </ul>
                    </div>
                </td>
                
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>