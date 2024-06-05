<form id="form-sync-cancel">
    <div class="row">
        <div class="col-md-offset-9 col-md-3">
            <div class="btn-group btn-group-sm pull-right" style="padding-bottom: 10px">
                <!-- <a href="javascript:void(0)" id="btn-sync-cancelation" class="btn btn-sm btn-success btn-sync-cancelation"><i
                            class="fa fa-refresh"></i>
                    &nbsp;Synchronize</a> -->
            </div>
        </div>
    </div>
    <table class="table table-bordered table-responsive  table-striped" id="table-cancel"
           data-page-length='10' data-order='[]' data-length-change="false">
        <thead>
        <tr>
            <th></th>
            <th>No Trip</th>
            <th>Tanggal Berangkat</th>
            <th>Tanggal Kembali</th>
            <th>Aktifitas</th>
            <th>Tujuan</th>
            <th>Keperluan</th>
            <th>Uang Muka</th>
            <th>Status</th>
            <th data-orderable="false"></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list_cancel as $d): ?>
            <?php 
                // if($d->no_trip != null && $d->no_trip != "" ){
                //     $id = null;
                // } else {
                //     $id = $d->id_travel_header ;
                // }
            ?>
            <tr>
                <td><?php echo $d->id_travel_header ?></td>
                <td><?php echo $d->no_trip ?></td>
                <td><?php echo $d->tanggal_berangkat ?></td>
                <td><?php echo $d->tanggal_kembali ?></td>
                <td><?php echo $d->activity_label ?></td>
                <td><?php echo $d->tujuan_lengkap ?></td>
                <td><?php echo $d->keperluan ?></td>
                <td><?php echo $this->generate->convert_rupiah($d->totalUM) ?></td>
                <td>
                    <?php //echo $d->status_label 
                        if($d->approval_level==99){
                            echo '<span class="label label-success">FINISH</span>';                         
                        }else if($d->approval_status==2){
                            echo '<span class="label label-danger">DITOLAK</span>'; 
                            if($d->approval_level==1){
                                echo '<p>Ditolak oleh Atasan 1</p>';
                            }elseif($d->approval_level==2){
                                echo '<p>Ditolak oleh Atasan 2</p>';
                            }elseif($d->approval_level==3){
                                echo '<p>Ditolak oleh Pejabat Berwenang</p>';
                            }elseif($d->approval_level==4){
                                echo '<p>Ditolak oleh Personalia/HROGA</p>';
                            }else{
                                echo '<p>-</p>';
                            }
                        }else{
                            echo '<span class="label label-warning">ON PROGRESS</span>'; 
                            if($d->approval_status==0){
                                echo '<p>Sedang diproses oleh Atasan 1</p>';
                            }else if(($d->approval_status==1)and($d->approval_level==2)){
                                echo '<p>Sedang diproses oleh Atasan 2</p>';
                            }else if(($d->approval_status==1)and($d->approval_level==4)){
                                echo '<p>Sedang diproses oleh Pejabat Berwenang</p>';
                            }else if(($d->approval_status==1)and($d->approval_level==3)){
                                echo '<p>Sedang diproses oleh HROGA</p>';
                            }else if($d->approval_status==3){
                                echo '<p>Menunggu Perbaikan Karyawan</p>';
                            }else{
                                echo '<p>-</p>';
                            }
                        }
                    ?>
                        
                </td>
                <td>
                    <div class='input-group-btn'>
                        <button type='button' class='btn btn-xs btn-default dropdown-toggle'
                                data-toggle='dropdown'><span class='fa fa-th-large'></span>
                        </button>
                        <ul class='dropdown-menu pull-right'>
                            <?php
                                echo '  
                                        <li>
                                            <a class="spd-detail" data-id="'.$d->id_travel_header.'" 
                                                href="javascript:void(0)"> <i class="fa fa-search"></i>&nbsp;Detail</a>
                                        </li>
                                    ';
                            ?>
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
                <a href="javascript:void(0)" id="btn-sync-cancelation" name="btn-sync-cancelation" 
                    class="btn btn-sm btn-success btn-sync-cancelation">
                    <i class="fa fa-refresh"></i>
                    &nbsp;Synchronize
                </a>
            </div>
        </div>
    </div>
</form>