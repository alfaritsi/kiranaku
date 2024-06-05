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
                        <?php if($leg_level_id == 2) : ?>
                        <div class="btn-group btn-group-sm pull-right">
                            <a href="javascript:void(0)" class="btn btn-sm btn-success" id="add-permintaan">
                                <i class="fa fa-plus-square"></i> &nbsp Permintaan
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table my-datatable-extends-order table-bordered table-striped" id="spk-table"
                               data-page-length="10" data-order="[]"
                               style="font-size: 12px;">
                            <thead>
                            <tr>
                                <th width="5%">Plant</th>
                                <th width="10%">Tanggal</th>
                                <th width="20%">User Input</th>
                                <th width="10%">Kategori</th>
                                <th width="10%">Nama Dokumen</th>
                                <th width="5%">Nomor Dokumen</th>
                                <th width="5%">Keperluan</th>
                                <th width="5%">Keterangan</th>
                                <th width="5%">Tanggal Kirim</th>
                                <th width="5%">Resi Dokumen</th>
                                <th width="5%">Status</th>
                                <th data-orderable="false" data-searchable="false" width="5%"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($list as $dt) {
                                $na = ($dt->na == 'n') ? "<i class='fa fa-check-square text-success'></i>" : "<i class='fa fa-minus-square text-danger'></i>";
                                echo "<tr>";
                                echo "<td>" . $dt->plant . "</td>";
                                echo "<td>" . $this->generate->generateDateFormat($dt->tanggal_buat) . "</td>";
                                echo "<td>" . $dt->user_input . " - " . $dt->nama . "</td>";
                                echo "<td>" . $dt->jenis_kategori . "</td>";
                                echo "<td>" . $dt->nama_permintaan_dok . "</td>";
                                echo "<td>" . $dt->nomor_dokumen . "</td>";
                                echo "<td>" . $dt->keperluan . "</td>";
                                echo "<td>" . $dt->keterangan . "</td>";
                                echo "<td>" . (isset($dt->tanggal_kirim) ? $this->generate->generateDateFormat($dt->tanggal_kirim) : '-') . "</td>";
                                echo "<td>" . $dt->table_resi . "</td>";
                                echo "<td><span class = 'badge " . $dt->warna . "'>" . ucfirst($dt->status) . "</span>&nbsp;";
                                echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default btn-sm dropdown-toggle' data-toggle='dropdown'><i class='fa fa-th-large'></i></button>
				                            <ul class='dropdown-menu pull-right'>";
                                echo $dt->links;
                                echo "    </ul>
				                          </div>
				                        </td>";
                                echo "</tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php $this->load->view('permintaan/includes/modal_resi') ?>
<?php $this->load->view('permintaan/includes/modal_result') ?>
<?php $this->load->view('permintaan/includes/modal_permintaan', compact('plant')) ?>
<?php $this->load->view('permintaan/includes/modal_approval') ?>
<?php $this->load->view('footer') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/apps/css/spk/spk.global.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker3.min.css"/>
<script src="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/spk/spk.global.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/spk/permintaan_manage.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>