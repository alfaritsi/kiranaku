<form id="form-refund">
    <table class="table table-bordered table-responsive" id="table-refund"
               data-page-length='10' data-order='[]' data-length-change="false"> <!-- //my-datatable-extends-order -->
        <?php 
            // $arraydata = $list;
            // $a=0;
            // $tipe_sc = "";
            // foreach ($tipe_screen as $dt){
            //     $a++;
            //     $tipe_sc    = $dt;
            //     break 1;
            // }
            
        ?>
        <thead>
            <tr>
                
                <th></th>
                <th>No Trip</th>
                <th>Aktifitas</th>
                <th>Tujuan</th>
                <th>Berangkat</th>
                <th>Transportasi</th>
                <th>Tiket</th>
                <th>Status</th>
                
                <!-- <th data-orderable="false" width="5%"></th> -->
            </tr>
        </thead>
        <tbody>
            <?php 
                foreach ($refund as $d): 
                    // $exp_dtl = isset($d->details_trip)? explode(, string)
                    //$rowspan = count($d->details_trip);
                    $tujuan = isset($d->tujuan) ? $d->tujuan : "";
                    $status = isset($d->has_refund) && $d->has_refund == '1' ? "<span class='label label-success'>Sudah direfund</span>" : "<span class='label label-warning'>Sudah direfund</span>";
                    $tiket  = isset($d->no_tiket) && $d->no_tiket != ""  ? 
                                                    "<a class='fileinput-exists fileinput-zoom' target='_blank' data-fancybox='' href='".base_url()."assets/file/travel/".$d->lampiran."'>".$d->no_tiket."</a>" : "";
            ?>
                <tr>
                    
                    <td><?php  ?></td>
                    <td><?php echo $d->no_trip; ?></td>
                    <td><?php echo $d->activity_label; ?></td>
                    <td><?php echo $tujuan; ?></td>
                    <td><?php echo date('d.m.Y', strtotime($d->dt_start)); ?></td>
                    <td><?php echo $d->jenis_kendaraan; ?></td>
                    <td><?php echo $tiket; ?></td>
                    <td><?php echo $status; ?></td>
                    
                </tr>
            <?php endforeach; ?>

        </tbody>
        
    </table> 

    <div class="row">
        <div class="form-group">
            <div class="col-md-12 text-center">                
                <a href="javascript:void(0)" id="btn-refund-spd" name="btn-refund-spd" 
                    class="btn btn-sm btn-success btn-refund-spd">
                        <i class="fa fa-refresh"></i>&nbsp;Submit
                </a>
                <!--<a href="javascript:void(0)" class="btn btn-sm btn-success btn-export-spd"
                    data-export="sync_pengajuan" id="excel_button_pengajuan"> 
                        <i class="fa fa-download"></i>&nbsp;Export To Excel</a> -->
            </div>
        </div>
    </div>
</form>