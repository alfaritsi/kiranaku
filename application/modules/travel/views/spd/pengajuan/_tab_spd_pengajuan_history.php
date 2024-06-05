<form name="filter-history" method="post">
    <div class="row">
        <div class="col-md-4 col-md-offset-8">
            <div class="form-group">
                <div class="input-group input-daterange" id="filter-date">
                    <label class="input-group-addon" for="tanggal-awal_filter">Tanggal</label>
                    <input type="text" id="tanggal_awal_filter"
                           name="tanggal_awal" class="form-control"
                           autocomplete="off" value="<?php echo $this->generate->generateDateFormat($tanggal_awal); ?>">
                    <label class="input-group-addon" for="tanggal-awal_filter">-</label>
                    <input type="text" id="tanggal_akhir_filter"
                           name="tanggal_akhir" class="form-control"
                           autocomplete="off" value="<?php echo $this->generate->generateDateFormat($tanggal_akhir); ?>">
                </div>
            </div>
        </div>
    </div>
</form>
<table class="table table-bordered table-responsive my-datatable-extends-order table-striped" id="table-pengajuan-history"
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
    <?php foreach ($list_history as $d): ?>
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
					if($d->approval_level==99){
						echo '<span class="label label-success">FINISH</span>'; 						
					}else if($d->approval_status==2){
						echo '<span class="label label-danger">DITOLAK</span>'; 
						if($d->approval_level==0){
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
						}else if(($d->approval_status==1)and($d->approval_level==99)){
							echo '<p>Sedang diproses oleh HROGA</p>';
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