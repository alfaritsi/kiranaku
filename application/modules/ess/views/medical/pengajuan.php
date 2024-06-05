<?php
/**
 * @application  : ESS Medical Pengajuan - View
 * @author       : Octe Reviyanto Nugroho
 * @contributor  :
 *     1. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     2. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     etc.
 */

$this->load->view('header')
?>

<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="nav-tabs-custom tab-success">
                    <ul class="nav nav-tabs pull-right">
                        <li role="presentation" class="pull-right">
                            <form method="post" class="navbar-form">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" name="tahun" class="monthPicker form-control"
                                           autocomplete="off" value="<?php echo $tahun; ?>">
                                </div>
                            </form>
                        </li>
                        <li class="active">
                            <a href="#tab-jalan" data-toggle="tab">Rawat Jalan</a>
                        </li>
                        <li>
                            <a href="#tab-inap" data-toggle="tab">Rawat Inap</a>
                        </li>
                        <li>
                            <a href="#tab-bersalin" data-toggle="tab">Bersalin</a>
                        </li>
                        <li>
                            <a href="#tab-frame" data-toggle="tab">Frame</a>
                        </li>
                        <li>
                            <a href="#tab-lensa" data-toggle="tab">Lensa</a>
                        </li>
                        <li class="pull-left header"><?php echo $title; ?></li>
                    </ul>
                    <div class="tab-content">
                        <?php
                        if (
                            isset($cutoff) &&
                            date_create($cutoff->jadwal)
                                ->modify('-7 days') <=
                            date_create(date('Y-m-d H:i'))&&
							$tahun == date('Y') 
                        ):
                            $jadwal = date_create($cutoff->jadwal);
                            $tahunGanti = new DateTime('1st January Next Year');
                            ?>
                            <div class="alert alert-info alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                            aria-hidden="true">&times;</span></button>
                                <h4><i class="icon fa fa-info"></i> Peringatan!</h4>
                                <p>
									Cut Off Bantuan Medical Kesehatan akan dilakukan pada 
									<b>tanggal <?php echo $jadwal->format('d.m.Y'); ?> 
									Pukul <?php echo $jadwal->format('H:i'); ?></b>. 
									Semua pengajuan yang diajukan setelah tanggal cut off tersebut akan menggunakan 
									<b>PLAFON KESEHATAN TAHUN <?php echo $tahunGanti->format('Y'); ?> </b>
									dan untuk semua pengajuan setelah jadwal tersebut akan di rubah tanggal pengajuan nya menjadi 
									<b><?php echo $tahunGanti->format('d.m.Y'); ?></b>
                                </p>
                            </div>
															
                        <?php endif; ?>
                        <div class="tab-pane active" id="tab-jalan">
                            <?php
                            echo $tab_pengajuan_jalan;

                            $this->load->view('_modal_pengajuan_jalan', array(
                                'nama_karyawan' => $nama_karyawan,
                                'data_keluarga' => $data_keluarga,
                                'jenis_sakit' => $jenis_sakit,
                                'sisa_fbk_jalan' => $sisa_fbk_jalan,
                                'sisa_fbk_jalan_next' => $sisa_fbk_jalan_next,
								'is_cutoff' => (isset($cutoff)&& $cutoff->jadwal<=date('Y-m-d H:i:s') )
                            ));
                            ?>
                        </div>
                        <div class="tab-pane" id="tab-inap">
                            <?php
                            echo $tab_pengajuan_inap;

                            $this->load->view('_modal_pengajuan_inap', array(
                                'nama_karyawan' => $nama_karyawan,
                                'data_keluarga' => $data_keluarga,
                                'jenis_sakit' => $jenis_sakit,
                                'plafon_fbk_inap' => $plafon_fbk_inap
                            ));
                            ?>
                        </div>
                        <div class="tab-pane" id="tab-bersalin">
                            <?php
                            echo $tab_pengajuan_bersalin;

                            $this->load->view('_modal_pengajuan_bersalin', array(
                                'nama_karyawan' => $nama_karyawan,
                                'data_keluarga' => $data_keluarga,
                                'plafon_bersalin_normal' => $plafon_bersalin_normal,
                                'plafon_bersalin_cesar' => $plafon_bersalin_cesar
                            ));
                            ?>
                        </div>
                        <div class="tab-pane" id="tab-frame">
                            <?php
                            echo $tab_pengajuan_frame;

                            $this->load->view('_modal_pengajuan_frame', array(
                                'nama_karyawan' => $nama_karyawan,
                                'data_keluarga' => $data_keluarga,
                                'sisa_fbk_frame' => $sisa_fbk_frame
                            ));
                            ?>
                        </div>
                        <div class="tab-pane" id="tab-lensa">
                            <?php
                            echo $tab_pengajuan_lensa;

                            $this->load->view('_modal_pengajuan_lensa', array(
                                'nama_karyawan' => $nama_karyawan,
                                'data_keluarga' => $data_keluarga,
                                'sisa_fbk_lensa' => $sisa_fbk_lensa
                            ));
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $this->load->view('_modal_history') ?>
<?php $this->load->view('_modal_detail') ?>
<?php $this->load->view('_modal_kwitansi') ?>
<?php $this->load->view('footer') ?>
<script>
    var tanggal_join_allowed = "<?php echo $tanggal_tetap;?>";
</script>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/jasny-bootstrap/css/jasny-bootstrap.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.css"/>
<script src="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker3.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/apps/css/ess/ess-global.css"/>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/jasny-bootstrap/js/jasny-bootstrap.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/numeric/autonumeric.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/ess/ess-global.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/ess/medical_pengajuan.js"></script>
