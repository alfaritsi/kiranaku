<table class="table table-bordered table-responsive my-datatable-extends-order table-striped" id="table-pengajuan"
       data-page-length='10' data-order='[[3,"desc"]]' data-length-change="false">
    <thead>
    <tr>
        <th>No Trip</th>
        <th>Tujuan</th>
		<th>Aktifitas</th>
		<th>Berangkat</th>
        <th>Kembali</th>
        <th>Jumlah Biaya</th>
        <th>Dibayarkan</th>
        <th>Status</th>
        <th data-orderable="false"></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($list as $d): ?>
		<?php 
		if($d->deklarasi_approval_level!=99){
		?>
        <tr>
            <td><?php echo $d->no_trip ?></td>
            <td><?php echo $d->tujuan_lengkap ?></td>
			<td><?php echo $d->activity_label ?></td>
            <td><?php echo $d->tanggal_berangkat ?></td>
            <td><?php echo $d->tanggal_kembali ?></td>
            <td><?php echo $this->generate->convert_rupiah($d->total_biaya) ?></td>
            <td><?php echo $this->generate->convert_rupiah($d->total_bayar) ?></td>
            <!--<td><?php echo $d->status_label ?></td>-->
            <td>
				<?php
					if(isset($d->deklarasi_approval_status)){
						if($d->deklarasi_approval_level==99){
							echo '<span class="label label-success">FINISH</span>'; 						
						}else if($d->deklarasi_approval_status==2){
							echo '<span class="label label-danger">DITOLAK</span>'; 
							if($d->deklarasi_approval_level==1){
								echo '<p>Ditolak oleh Atasan 1</p>';
							}elseif($d->deklarasi_approval_level==3){
								echo '<p>Ditolak oleh Pejabat Berwenang</p>';
							}elseif($d->deklarasi_approval_level==4){
								echo '<p>Ditolak oleh Personalia/HROGA</p>';
							}else{
								echo '<p>-</p>';
							}
						}else{
							echo '<span class="label label-warning">ON PROGRESS</span>'; 
							if($d->deklarasi_approval_level==1){
								echo '<p>Sedang diproses oleh Atasan 1</p>';
							}else if($d->deklarasi_approval_level==2){
								echo '<p>Sedang diproses oleh Atasan 2</p>';
							}else if($d->deklarasi_approval_level==3){
								echo '<p>Sedang diproses oleh Personalia/HROGA</p>';
							}else if($d->deklarasi_approval_level==4){
								echo '<p>Sedang diproses oleh Pejabat Berwenang</p>';
							}else if(($d->deklarasi_approval_level==0)and($d->deklarasi_approval_status==3)){
								echo '<p>Menunggu Perbaikan Karyawan</p>';
							}else{
								echo '<p>-</p>';
							}
						}
					}else{
						echo '<span class="label label-info">Deklarasi</span>';
					}
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
		<?php 
		}
		?>
    <?php endforeach; ?>
    </tbody>
</table>