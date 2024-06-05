<?php
$this->load->view('header')
?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-sm-8">
                <div class="box box-success">
                    <div class="box-header">
                        <div class="box-tools">
                            <a class="btn btn-xs btn-success" href="<?php echo base_url('spk/master/jenisvendor') ?>">Kembali</a>
                        </div>
                        <h3 class="box-title"><strong>List <?php echo $title; ?></strong></h3>
                        <h5><strong>Jenis Vendor :</strong> <?php echo $jenisvendor->jenis_vendor ?></h5>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table my-datatable-extends-order table-bordered" id="menus-table"
                               data-page-length="10"
                               style="font-size: 12px;">
                            <thead>
                            <tr>
                                <th width="30%">Nama Dokumen</th>
                                <th width="10%">&nbsp;</th>
                                <th width="5%">Aktif</th>
                                <th data-orderable="false" data-searchable="false" width="5%"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($list as $dt) {
                                $na = ($dt->na == 'n') ? "<i class='fa fa-check-square text-success'></i>" : "<i class='fa fa-minus-square text-danger'></i>";
                                echo "<tr>";
                                echo "<td>" . $dt->nama_dokumen . "</td>";
                                echo "<td class='text-center'>" . $dt->mandatory . "</td>";
                                echo "<td class='text-center'>" . $na . "</td>";
                                echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default btn-sm dropdown-toggle' data-toggle='dropdown'><i class='fa fa-th-large'></i></button>
				                            <ul class='dropdown-menu pull-right'>";
                                if ($dt->na == 'n') {
                                    echo "
                <li><a href='#' class='edit' data-edit='" . $dt->id_oto_vendor . "'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
                <li><a href='#' class='delete' data-delete='" . $dt->id_oto_vendor . "'><i class='fa fa-trash-o'></i> Hapus</a></li>
                  ";
                                }
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
            <div class="col-sm-4">

                <div class="nav-tabs-custom" id="tabs-edit">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab-edit" data-toggle="tab">
                                <strong class="title-form">
                                    Form <?php echo(isset($title_form) ? $title_form : $title); ?>
                                </strong>
                            </a>
                        </li>
                    </ul>
                    <form role="form" class="form-oto-vendor" enctype="multipart/form-data">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab-edit">
                                <div class="col-sm-12" style="margin-top: 20px;">
                                    <button type="button" class="btn btn-sm btn-default pull-right hidden btn-new">
                                        Buat <?php echo(isset($title_form) ? $title_form : $title); ?> Baru
                                    </button>
                                </div>
                                <div class="box-body">
									<!--
                                    <div class="form-group">
                                        <label for="nama_dokumen_vendor">Nama Dokumen</label>
                                        <div>
                                            <input type="text" class="form-control" name="nama_dokumen_vendor"
                                                   id="nama_dokumen_vendor"
                                                   placeholder="Masukkkan Nama Dokumen" required="required">
                                        </div>
                                    </div>
									-->
									<div class="form-group">
										<label> Nama Dokumen: </label>
                                        <input type="hidden" class="form-control" name="nama_dokumen_vendor" id="nama_dokumen_vendor" placeholder="Masukkkan Nama Dokumen" required="required">
										<select class="form-control select2" id="id_master_dokumen" name="id_master_dokumen" style="width: 100%;" data-placeholder="Pilih Nama Dokumen" required="required">
											<?php
												echo "<option value=''>Pilih Nama Dokumen</option>";
												foreach($master_dokumen as $dt){
													echo "<option value='".$dt->id_master_dokumen."'>".$dt->nama."</option>";
												}
											?>
										</select>
									</div>
                                    <div class="form-group">
                                        <label for="mandatory">Mandatory</label>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="mandatory" id="mandatory_y" value="Mandatory"
                                                       checked>&nbsp;Mandatory
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="mandatory" id="mandatory_n"
                                                       value="Tidak Mandatory">&nbsp;Tidak Mandatory
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <input type="hidden" name="id_jenis_vendor"
                                   value="<?php echo $jenisvendor->id_jenis_vendor; ?>">
                            <input type="hidden" name="id_oto_vendor">
                            <button type="button" name="action_btn" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/spk/master_oto_vendor.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>