<!--
/*
@application  : Equipment Management
@author     : Lukman Hakim (7143)
@contributor  :
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/
 -->

<?php $this->load->view('header') ?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-sm-8">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title"><strong>Master Detail Aset</strong></h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered table-striped datatable-custom">
                            <thead>
                            <tr>
                                <th>Nama Kolom</th>
                                <th>Nama</th>
                                <th>Satuan</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($details as $k) {
                                echo "<tr>";
                                echo "<td>" . $k->nama_kolom . "</td>";
                                echo "<td>" . $k->nama . "</td>";
                                echo "<td>" . $k->satuan . "</td>";
                                echo "<td>";
                                if ($k->na == 'n') {
                                    echo "<span class='label label-success'>ACTIVE</span>";
                                }
                                if ($k->na == 'y') {
                                    echo "<span class='label label-danger'>NOT ACTIVE</span>";
                                }
                                echo "</td>";
                                echo "<td>
						                          <div class='input-group-btn'>
						                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
						                            <ul class='dropdown-menu pull-right'>";
                                if ($k->na == 'n') {
                                    echo "<li><a href='javascript:void(0)' class='edit_master' data-edit='" . $k->id_aset_detail_master . "'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
						                        	  <li><a href='javascript:void(0)' class='non_active' data-non_active='" . $k->id_aset_detail_master . "'><i class='fa fa-times'></i> Non Aktif</a></li>
						                              <li><a href='javascript:void(0)' class='delete' data-delete='" . $k->id_aset_detail_master . "'><i class='fa fa-trash-o'></i> Hapus</a></li>";
                                }
                                if ($k->na == 'y') {
                                    echo "<li><a href='javascript:void(0)' class='set_active' data-set_active='" . $k->id_aset_detail_master . "'><i class='fa fa-check'></i> Set Aktif</a></li>";
                                }
                                echo "</ul>
						                          </div>
						                        </td>";
                                echo "</tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--end box-->
            </div>

            <div class="col-sm-4">
                <div class="box box-success" id="box-add-komponen">
                    <div class="box-header with-border">
                        <h3 class="box-title title-form-opsi"><strong>Buat Detail Aset</strong></h3>
                        <button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new-opsi">
                            Buat Detail Aset Baru
                        </button>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" class="form-master">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="kolom_aset">Nama Kolom Aset</label>
                                <input type="text" class="form-control" name="nama_kolom" id="nama_kolom"
                                    placeholder="Masukkan nama kolom dari table">
                            </div>
                            <div class="form-group">
                                <label for="merk">Label Detail</label>
                                <input type="text" class="form-control" name="nama" id="nama"
                                       required="required" placeholder="Masukkkan Label Detail">
                            </div>
                            <div class="form-group">
                                <label for="satuan">Satuan</label>
                                <input type="text" class="form-control" name="satuan" id="satuan"
                                       placeholder="Masukkkan Satuan">
                                <small class="help-block">
                                    Contoh: GHz, GB, TB dll.<br/>
                                    Kosongi bila tidak diperlukan.
                                </small>
                            </div>
                        </div>
                        <div class="box-footer">
                            <input type="hidden" name="id_aset_detail_master">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/asset/master/aset_detail.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>


