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
                        <h3 class="box-title"><strong>Master Detail Aset Opsi</strong></h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered table-striped datatable-custom">
                            <thead>
                            <tr>
                                <th>Nama Kolom</th>
                                <th>Nilai Pilihan</th>
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
                                echo "<td>" . $k->nilai_pilihan . "</td>";
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
                                    echo "<li><a href='javascript:void(0)' class='edit_opsi' data-edit-opsi='" . $k->id_aset_detail_opsi . "'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
						                        	  <li><a href='javascript:void(0)' class='non_active' data-tab='komponen' data-non_active='" . $k->id_aset_detail_opsi . "'><i class='fa fa-times'></i> Non Aktif</a></li>
						                              <li><a href='javascript:void(0)' class='delete' data-tab='komponen' data-delete='" . $k->id_aset_detail_opsi . "'><i class='fa fa-trash-o'></i> Hapus</a></li>";
                                }
                                if ($k->na == 'y') {
                                    echo "<li><a href='javascript:void(0)' class='set_active' data-tab='komponen' data-set_active='" . $k->id_aset_detail_opsi . "'><i class='fa fa-check'></i> Set Aktif</a></li>";
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
                        <h3 class="box-title title-form-opsi"><strong>Buat Opsi Detail Aset</strong></h3>
                        <button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new-opsi">
                            Buat Opsi Detail Aset Baru
                        </button>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" class="form-master-komponen">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="kolom_aset">Nama Kolom Aset</label>
                                <select class="form-control select2" name="id_aset_detail_master" id="id_aset_detail_master"
                                        data-placeholder="Pilih salah satu" data-allow-clear="true">
                                    <option></option>
                                    <?php foreach ($masters as $master) : ?>
                                        <option value="<?php echo $master->id_aset_detail_master?>"><?php echo '['.$master->nama_kolom.'] '.$master->nama?></option>
                                    <?php endforeach; ?>
<!--                                    <option value="OS">OS</option>-->
<!--                                    <option value="OFFICE_APPS">OFFICE APPS</option>-->
<!--                                    <option value="RAM">RAM</option>-->
<!--                                    <option value="HDD">HDD</option>-->
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="merk">Nilai Pilihan</label>
                                <div id="groupNilai">
                                    <input type="text" class="form-control" name="nilai_pilihan" id="nilai_pilihan"
                                           required="required" placeholder="Masukkkan Nama Pilihan">
                                    <span class="input-group-addon hide"></span>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <input type="hidden" name="id_aset_detail_opsi">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/asset/master/aset_detail_opsi.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>


