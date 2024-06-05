<div class="row">
    <div class="col-md-offset-9 col-md-3">
        <div class="btn-group btn-group-sm pull-right" style="padding-bottom: 10px">
            <!-- <a href="javascript:void(0)" id="btn-add-pengajuan" class="btn btn-sm btn-success btn-add-pengajuan"><i
                        class="fa fa-plus-square"></i>
                &nbsp;Tambah Pengajuan</a> -->
            <a href="<?php echo base_url() . "travel/spd/add"?>" class="btn btn-sm btn-success" target="_blank"><i
                    class="fa fa-plus-square"></i>
                &nbsp;Tambah Pengajuan</a>
            <!-- <a href="javascript:void(0)" id="btn-add-pengajuan-2" class="btn btn-sm btn-success btn-add-pengajuan"><i
                        class="fa fa-plus-square"></i>
                &nbsp;Tambah Pengajuan2</a> -->
        </div>
    </div>
</div>
<table class="table table-bordered table-responsive my-datatable-extends-order table-striped" id="table-pengajuan"
       data-page-length='10' data-order='[[4,"desc"]]' data-length-change="false">
    <thead>
    <tr>
        <th>No Trip</th>
        <th>Tujuan</th>
		<th>Aktifitas</th>
        <th>Keperluan</th>
        <th>Berangkat</th>
        <th>Kembali</th>
        <!--<th>Uang Muka</th>-->
        <th>Status</th>
        <th data-orderable="false"></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($list as $d): ?>
        <tr>
            <td><?php echo $d->no_trip ?></td>
            <td><?php echo $d->tujuan_lengkap ?></td>
			<td><?php echo $d->activity_label ?></td>
            <td><?php echo $d->keperluan ?></td>
            <td><?php echo $d->tanggal_berangkat ?></td>
            <td><?php echo $d->tanggal_kembali ?></td>
            <!--<td><?php echo $this->generate->convert_rupiah($d->totalUM) ?></td>-->
            <td>
				<?php
					if(($d->approval_level==99)and($d->status_transportasi==1)){
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
						if($d->approval_level==1){
							echo '<p>Sedang diproses oleh Atasan 1</p>';
						}else if($d->approval_level==2){
							echo '<p>Sedang diproses oleh Atasan 2</p>';
						}else if($d->approval_level==3){
							echo '<p>Sedang diproses oleh Personalia/HROGA</p>';
						}else if($d->approval_level==4){
							if($d->no_trip>0){
								echo '<p>Menunggu PIC Pemesanan Tiket</p>';
							}else{
								echo '<p>Sedang diproses oleh Pejabat Berwenang</p>';
							}
						}else if($d->approval_status==3){
							echo '<p>Menunggu Perbaikan Karyawan</p>';
						}else{
							echo '<p>-</p>';
						}
					}
				?>
			</td>
            <!--<td><?php echo $d->status_label ?></td>-->
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