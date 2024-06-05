<table class="table table-bordered table-responsive my-datatable-extends-order table-striped" id="table-deklarasi"
       data-page-length='10' data-order='[[6,"desc"]]' data-length-change="false">
    <thead>
    <tr>
        <th>No Trip</th>
        <th>NIK</th>
        <th>Nama</th>
        <th>Tujuan</th>
		<th>Aktifitas</th>
		<th>Keperluan</th>
        <th>Berangkat</th>
        <th>Kembali</th>
        <th>Dibayarkan</th>
        <th>Status</th>
        <th data-orderable="false"></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($list_deklarasi as $d): ?>
        <tr>
            <td><?php echo $d->no_trip ?></td>
            <td><?php echo $d->nik ?></td>
            <td><?php echo $d->nama_karyawan ?></td>
            <td><?php echo $d->tujuan_lengkap ?></td>
			<td><?php echo $d->activity_label ?></td>
            <td><?php echo $d->keperluan ?></td>
			<td><?php echo $d->tanggal_berangkat ?></td>
            <td><?php echo $d->tanggal_kembali ?></td>
            <td><?php echo $this->generate->convert_rupiah($d->total_bayar) ?></td>
            <td>
				<?php
					if($d->approval_status==5){
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