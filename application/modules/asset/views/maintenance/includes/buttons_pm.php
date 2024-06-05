<div class='input-group-btn'>
    <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
    <ul class='dropdown-menu pull-right'>

        <?php if ($pengguna == 'fo' && $akses_pm == 'OPERATOR') : ?>
            <?php if ($main_status == 'scheduled') : ?>
                <?php if ($jenis_tindakan == 'perawatan' && date_format(date_create($jadwal_service), 'Ymd') <= date('Ymd')) : ?>
                    <li>
                        <a href='javascript:void(0)' class='pm' data-main='<?php echo $this->generate->kirana_encrypt($id_main) ?>'>
                            <i class='fa fa-calendar-o'></i> Preventive Maintenance
                        </a>
                    </li>
                <?php else : ?>
                    <!--                <li>-->
                    <!--                    <a href='javascript:void(0)' class='perbaikan'-->
                    <!--                       data-main='--><?php //echo $this->generate->kirana_encrypt($id_main) 
                                                                ?>
                    <!--'>-->
                    <!--                        <i class='fa fa-wrench'></i> Perbaikanssss-->
                    <!--                    </a>-->
                    <!--                </li>-->
                <?php endif; ?>
                <?php if (base64_decode($this->session->userdata("-ho-")) == 'y') : ?>
                    <li>
                        <a href='javascript:void(0)' class='delete' data-delete='<?php echo $this->generate->kirana_encrypt($id_main) ?>'>
                            <i class='fa fa-times text-danger'></i> Hapus Jadwal
                        </a>
                    </li>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if ($pengguna == 'fo' && $main_status == 'confirmpic' && (($akses_pm == 'PIC ALAT BERAT' && $id_kategori == '1') || ($akses_pm == 'PIC APAR' && $id_kategori == '4'))) : ?>
            <li>
                <?php $link_konfirm = base_url() . "asset/maintenance/konfirmasi/" . $this->generate->kirana_encrypt($id_main); ?>
                <a href='<?php echo $link_konfirm ?>' target='_blank'>
                    <i class='fa fa-check'></i> Konfirmasi
                </a>
            </li>
        <?php endif; ?>
        <?php if ($pengguna == 'fo' && $main_status !== 'scheduled') : ?>
            <li>
                <a href='javascript:void(0)' class='detail' data-main='<?php echo $this->generate->kirana_encrypt($id_main) ?>'>
                    <i class='fa fa-list'></i> Detail
                </a>
            </li>
        <?php endif; ?>
        <?php if ($pengguna == 'fo' && $main_status == 'scheduled' && $akses_pm !== 'OPERATOR' && $akses_pm !== 'Viewer HO') : ?>
            <li>
                <a href='javascript:void(0)' class='ganti_operator' data-op='<?php echo $nama_operator ?>' data-plant='<?php echo $kode ?>' data-main='<?php echo $this->generate->kirana_encrypt($id_main) ?>'>
                    <i class='fa fa-user'></i> Ganti Operator
                </a>
            </li>
        <?php endif; ?>
        <?php if ($pengguna !== 'fo') : ?>
            <?php if ($main_status == 'scheduled') : ?>
                <?php if ($jenis_tindakan == 'perawatan') : ?>
                    <li>
                        <a href='javascript:void(0)' class='pm' data-main='<?php echo $this->generate->kirana_encrypt($id_main) ?>'>
                            <i class='fa fa-calendar-o'></i> Preventive Maintenance
                        </a>
                    </li>
                <?php else : ?>
                <?php endif; ?>
                <?php if (base64_decode($this->session->userdata("-ho-")) == 'y') : ?>
                    <li>
                        <a href='javascript:void(0)' class='delete' data-delete='<?php echo $this->generate->kirana_encrypt($id_main) ?>'>
                            <i class='fa fa-times text-danger'></i> Hapus Jadwal
                        </a>
                    </li>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
        <li>
            <a href='javascript:void(0)' class='history' data-aset='<?php echo $this->generate->kirana_encrypt($id_aset) ?>'>
                <i class='fa fa-history text-info'></i> History maintenance
            </a>
        </li>
    </ul>
</div>