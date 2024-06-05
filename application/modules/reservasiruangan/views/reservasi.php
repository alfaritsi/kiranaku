<?php
$this->load->view('header')
?>

<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-sm-12">


                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form method="get" id="form-filter">
                            <div class="row">
                                <div class='col-md-5'>
                                    <table class='table table-bordered'>
                                        <tr>
                                            <td bgcolor='#ccc' width='20'></td>
                                            <td width='130'>Tersedia</td>
                                            <td bgcolor='#00a65a' width='20'></td>
                                            <td width='130'>Sudah dipesan</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="form-group col-md-offset-4 col-md-3">
                                    <div class="input-group">
                                        <label class="input-group-addon">Tanggal</label>
                                        <input type="text" id="filter-tgl" name="tgl"
                                               readonly
                                               value="<?php echo date('d.m.Y', strtotime($tgl)) ?>"
                                               class="form-control datepicker"/>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class='row'>
                            <div class='col-md-12 table-responsive'>
                                <table id="table-timetable" class='table table-bordered table-striped'>
                                    <thead>
                                    <tr>
                                        <th width='15%'>Ruang</th>
                                        <?php
                                        foreach ($jam as $item) {
                                            echo "<th width='150' align='center'>$item->jam_awal - $item->jam_akhir</th>";
                                        }
                                        ?>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($datas as $data): ?>
                                        <tr>
                                            <td>
                                                <strong data-toggle="collapse" href="#fasilitas-<?php echo $data->id_ruang;?>" aria-expanded="false">
                                                    <?php echo $data->nama ?>
                                                    <i class="fa fa-info-circle pull-right"></i>
                                                </strong>
                                                <div class="text-small collapse" id="fasilitas-<?php echo $data->id_ruang;?>">
                                                    <?php echo $data->available_fasilitas; ?>
                                                </div>
                                            </td>
                                            <?php
                                            $last_user = "";
                                            $last_keperluan = "";
                                            ?>
                                            <?php foreach ($data->available_hours as $hour) :
                                                $canEdit = false;
                                                ?>
                                                <?php if ($hour->reserved) :
                                                    $canEdit = $hour->reservasi->id_karyawan == base64_decode($this->session->userdata("-id_karyawan-"));
                                                    ?>

                                                        <td class="slot slot-reserved <?php echo ($canEdit)?'editable':''; ?>"
                                                            colspan="1"
                                                            data-id="<?php echo $this->generate->kirana_encrypt($hour->reservasi->id_reservasi); ?>"
                                                            data-tgl="<?php echo $tgl; ?>"
                                                            data-id_ruang="<?php echo $this->generate->kirana_encrypt($data->id_ruang)?>"
                                                            data-id_karyawan="<?php echo $this->generate->kirana_encrypt($hour->reservasi->id_karyawan)?>"
                                                            data-keperluan="<?php echo $this->generate->kirana_encrypt($hour->reservasi->keperluan)?>"
                                                            data-jam_awal_reservasi="<?php echo $hour->jam_awal_reservasi; ?>"
                                                            data-jam_akhir_reservasi="<?php echo $hour->jam_akhir_reservasi; ?>"
                                                        >
                                                            <p>
                                                                <strong>
                                                                    <?php echo $hour->reservasi->nama_karyawan; ?>
                                                                </strong><br/>
                                                                <?php echo $hour->reservasi->keperluan; ?>
                                                            </p>
                                                        </td>

                                                <?php else: ?>
                                                    <td class="slot slot-free"
                                                        data-id=""
                                                        data-tgl="<?php echo $tgl; ?>"
                                                        data-id_ruang="<?php echo $this->generate->kirana_encrypt($data->id_ruang)?>"
                                                        data-jam_awal_reservasi="<?php echo $hour->jam_awal; ?>"
                                                        data-jam_akhir_reservasi=""
                                                    >&nbsp;</td>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="modal-reservasi">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Reservasi Ruang <span id="nama-ruangan"></span></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal form-reservasi">
                    <input type="hidden" id="id_ruang" name="id_ruang">
                    <input type="hidden" id="tanggal" name="tanggal">
                    <div class="row">
                        <div class="col-md-12">
                            <table id="detail-reservator" class="hide table table-striped">
                                <tr>
                                    <td rowspan="3" width="100">
                                        <img id="user-image" class="img-thumbnail iimage" />
                                    </td>
                                    <td class="text-bold">NIK</td>
                                    <td class="inik"></td>
                                </tr>
                                <tr>
                                    <td class="text-bold">Nama</td>
                                    <td class="inama"></td>
                                </tr>
                                <tr>
                                    <td class="text-bold">Nomor Ext</td>
                                    <td class="itelepon"></td>
                                </tr>
                            </table>
                            <table class="table table-striped">
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <label for="keperluan" class=" col-md-4">Topik Meeting</label>
                                            <div class="col-md-8 ">
                                                <div id="div_keperluan" >
                                                    <textarea id="keperluan" class="form-control" name="keperluan" placeholder="Masukkkan keperluan meeting" required></textarea>
                                                </div>
                                                <p id="label_keperluan" class="form-control-static"><span class="value"></span> </p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="jumlah" class=" col-md-4">Jumlah Peserta</label>
                                            <div class="col-md-4">
                                                <div id="div_jumlah" class="input-group col-md-12">
                                                    <input type="number" class="form-control" name="jumlah" id="jumlah"
                                                           placeholder="Jumlah peserta"
                                                           max="0"
                                                           min="1"
                                                           required="required">
                                                </div>
                                                <p id="label_jumlah" class="form-control-static"><span class="value">8</span></p>
                                            </div>
                                            <p class="form-control-static col-md-2" style="padding-top:5px;">Orang</p>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <label for="kapasitas" class=" col-md-4">Kapasitas Ruang</label>
                                            <div class="col-md-8">
                                                <p id="kapasitas" class="form-control-static"><span class="value">8</span> Kursi</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="category_name" class=" col-md-4">Fasilitas</label>
                                            <div class="col-sm-7">
                                                <p id="fasilitas" class="form-control-static">

                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <label for="category_name" class=" col-md-4">Tanggal</label>
                                            <div class="col-md-8">
                                                <p id="tanggal" class="form-control-static"><span class="value">11.11.2000</span></p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="category_name" class=" col-md-4">Jam Awal</label>
                                            <div class="col-md-8">
                                                <input type="hidden" id="jam_awal" name="jam_awal" />
                                                <p id="label_jam_awal" class="form-control-static"><span class="value">11:00</span></p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="category_name" class=" col-md-4">Jam Akhir</label>
                                            <div class="col-md-3">
                                                <input type="hidden" id="jam_akhir_reservasi" name="jam_akhir_reservasi" />
                                                <select id="jam_akhir" name="jam_akhir" class="form-control select2" required></select>
                                                <p id="label_jam_akhir_reservasi" class="form-control-static"><span class="value">11:00</span></p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button name="batal_btn" class="btn btn-danger" style="display: none;" type="button">Batal Reservasi</button>
                <button name="reset_btn" class="btn btn-warning" type="reset">Reset</button>
                <button name="simpan_btn" class="btn btn-success" type="button">Simpan</button>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('footer') ?>
<style type="text/css">
    #table-timetable thead th {
        background-color: #00c0ef;
        color: white;
    }
    .slot {
        cursor: pointer;
    }

    .slot-reserved {
        color: white;
        background-color: #00a65a;
    }

    .slot-reserved.editable {
        color: white;
        background-color: #ff851b;
    }

    .slot-free {
        color: white;
        background-color: #ccc;
    }

    .form-control-static {
        padding-top: 0;
    }

    .list-fasilitas {
        font-size: 0.85em;
    }

    .form-group{
        margin-bottom: 5px !important;
    }
    .form-reservasi .table {
        margin-bottom: 5px;
    }
</style>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css"/>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/reservasiruangan/reservasi.js"></script>