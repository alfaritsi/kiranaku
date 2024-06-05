<?php if ($tahun == date('Y')) : ?>
    <div class="row">
        <?php if ($sisa_fbk_jalan > 0): ?>
            <div class="col-md-3">
                <h4>
                    <p class="badge bg-yellow">
                        <b><i class="fa fa-umbrella"></i> Rawat Jalan
                            : <?php echo $this->less->convert_rupiah($sisa_fbk_jalan); ?></b>
                    </p>
                </h4>
            </div>
            <div class="col-md-3 col-md-offset-6">
                <?php if ($tahun == date('Y')): ?>
                    <div class="btn-group btn-group-sm pull-right">
                        <a href="javascript:void(0)" id="btn-add-pengajuan-jalan"
                           class="btn btn-sm btn-success btn-add-pengajuan btn-block" data-form="Rawat Jalan">
                            <i class="fa fa-plus-square"></i> &nbsp; Form Rawat Jalan
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
			<?php 
				if(isset($cutoff)&& $cutoff->jadwal<=date('Y-m-d H:i:s') ){
					echo '
					<div class="col-md-3">
						<h4>
							<p class="badge bg-yellow">
								<b><i class="fa fa-umbrella"></i> Rawat Jalan
									: '.$this->less->convert_rupiah($sisa_fbk_jalan).'</b>
							</p>
						</h4>
					</div>
					<div class="col-md-3 col-md-offset-6">
							<div class="btn-group btn-group-sm pull-right">
								<a href="javascript:void(0)" id="btn-add-pengajuan-jalan"
								   class="btn btn-sm btn-success btn-add-pengajuan btn-block" data-form="Rawat Jalan">
									<i class="fa fa-plus-square"></i> &nbsp; Form Rawat Jalan
								</a>
							</div>
					</div>
					
					';	
				}else{
					echo '
					<div class="col-md-12">
						<div class="callout callout-warning">
							<p>
								<i class="fa fa-warning"></i> Plafon rawat jalan anda habis.
							</p>
						</div>
					</div>
					';
				}
			?>
        <?php endif; ?>
		
		
    </div>
<?php endif; ?>
<table class="table table-bordered table-responsive my-datatable-extends-order table-striped" id="table-pengajuan-jalan"
       data-page-length='10' data-order='[]' data-length-change="false">
    <thead>
    <tr>
        <th>No. Pengajuan</th>
        <th>Tanggal</th>
        <th>Jenis Sakit</th>
        <th>Total Klaim</th>
        <th>Detail Kwitansi</th>
        <th>Detail Disetujui</th>
        <th>Sisa Plafon</th>
        <th>Status</th>
        <th data-orderable="false"></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($list_jalan as $data):

        $plafon = $this->less->get_plafon_sisa(
            array(
                'tanggal_akhir' => date('Y-m-d', strtotime($data->tanggal_buat)),
                'tahun' => date('Y', strtotime($data->tanggal_buat)),
                'id_before' => $data->id_fbk,
                'nik' => $data->nik,
                'kode' => 'BRJL'
            )
        );

        ?>
        <tr>
            <td><?php echo $data->nomor ?></td>
            <td><?php echo $this->generate->generateDateFormat($data->tanggal_buat) ?></td>
            <td><?php echo $data->sakit ?></td>
            <td><?php echo $this->less->convert_rupiah($data->total_kwitansi) ?></td>
            <td>
                <a class="detail-kwitansi" href="javascript:void(0)" data-kwitansi='<?php echo $data->enId ?>'
                   data-disetujui="false">
                    <span class="badge bg-green"><?php echo count($data->kwitansi); ?> Kwitansi</span>
                </a>
            </td>

            <td>
                <a class="detail-kwitansi-disetujui" href="javascript:void(0)" data-kwitansi='<?php echo $data->enId ?>'
                   data-disetujui="true">
                    <span class="badge bg-blue"><?php echo count($data->kwitansi_disetujui); ?> Disetujui</span>
                </a>
            </td>
            <td>
                <?php
                if ($data->id_fbk_status == ESS_MEDICAL_STATUS_DISETUJUI)
                    echo $this->less->convert_rupiah($data->plafon_medical - $data->total_ganti);
                else
                    echo $this->less->convert_rupiah($plafon);
                ?>
            </td>
            <td><span class="badge <?php echo $data->warna ?>"><?php echo $data->nama_status ?></span></td>
            <td>
                <div class='input-group-btn'>
                    <button type='button' class='btn btn-xs btn-default dropdown-toggle' data-toggle='dropdown'><span
                                class='fa fa-th-large'></span></button>
                    <ul class='dropdown-menu pull-right'>
                        <?php if (in_array($data->id_fbk_status, array(ESS_MEDICAL_STATUS_MENUNGGU, ESS_MEDICAL_STATUS_TDK_LENGKAP))): ?>
                            <li><a href='javascript:void(0)' class='edit' data-edit='<?php echo $data->enId ?>'><i
                                            class='fa fa-edit'></i> Edit</a></li>
                            <li><a href='javascript:void(0)' class='delete' data-delete='<?php echo $data->enId ?>'
                                   data-action='delete_na'><i class='fa fa-trash'></i> Delete</a></li>
                        <?php else: ?>
                            <li><a href='javascript:void(0)' class='detail-medical'
                                   data-detail='<?php echo $data->enId ?>'><i
                                            class='fa fa-search'></i> Detail</a></li>
                        <?php endif; ?>
                        <?php if ($data->id_fbk_status != ESS_MEDICAL_STATUS_MENUNGGU): ?>
                            <li><a href='javascript:void(0)' class='history-medical'
                                   data-history='<?php echo $data->enId ?>'><i class='fa fa-history'></i> History</a>
                            </li>
                        <?php endif; ?>
                        <?php if ($data->id_fbk_status <> ESS_MEDICAL_STATUS_TDK_LENGKAP): ?>
                            <li class="divider" role="separator"></li>
                            <li><a href='javascript:void(0)' class='cetak-medical'
                                   data-cetak='<?php echo $data->enId ?>'><i
                                            class='fa fa-print'></i> Cetak</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>