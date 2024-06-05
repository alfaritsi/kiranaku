<?php
/**
 * @application  : Info Karyawan - View
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
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title"><strong><?php echo $title; ?></strong></h3>

                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
					
                        <div class="row">
						
                            <div class="col-sm-6" style="margin-bottom: 10px">
                                <form method="post" class="form-filter-lokasi">
									<label for="plant">Lokasi</label>
									<div class="checkbox pull-right select_all" style="margin:0; display: ;">
										<label><input type="checkbox" class="isSelectAllPlant"> Select All</label>
									</div>
									<select id="lokasi" name="lokasi[]" data-placeholder="Pilih lokasi karyawan" multiple>
										<option></option>
										<option value="KMTR"
											<?php
											echo in_array('KMTR',$selected_lokasi)?'selected':''
											?>
										>Head Office</option>
										<?php foreach ($lokasi_options as $lokasi_option) : ?>
										<option value="<?php echo $lokasi_option->plant; ?>"
											<?php
											echo in_array($lokasi_option->plant,$selected_lokasi)?'selected':''
											?>
										>
											<?php echo $lokasi_option->plant_name; ?>
										</option>
										<?php endforeach; ?>
									</select>
                                </form>
                            </div>
                        </div>
						<!--filter-->
						<div class="box-body">
							<table class="table table-bordered table-striped"
								   id="sspTable">
								<thead>
									<tr>
										<th>Id</th>
										<th>Nama</th>
										<th>NIK</th>
										<th>Kantor/Pabrik</th>
										<th>Bagian</th>
										<th>Email</th>
										<th>Ext</th>
										<th>#</th>
									</tr>
								</thead>
							</table>
						</div>
						<!--
                        <table class="table table-bordered my-datatable-extends-order" data-page="50">
                            <thead>
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>Kantor/Pabrik</th>
                            <th>Bagian</th>
                            <th>Email</th>
                            <th>Ext</th>
                            </thead>
                            <tbody>
                            <?php
                            // foreach ($karyawans as $dt) {
                                // $enId = $this->generate->kirana_encrypt($dt->id_karyawan);
                                // $na = ($dt->na == 'n') ? "<i class='fa fa-check-square'></i>" : "<i class='fa fa-minus-square'></i>";
                                // $kantor = ($dt->ho == 'y') ? "Head Office" : $dt->nama_pabrik;
                                // echo "<tr class='detail' data-edit='".$enId."'>";
                                // echo "<td>" . $dt->nama . "</td>";
                                // echo "<td>" . $dt->nik . "</td>";
                                // echo "<td>" . $kantor . "</td>";
                                // echo "<td>" . $dt->nama_bagian . "</td>";
                                // echo "<td><a href='mailto:" . $dt->email . "' >$dt->email</a></td>";
                                // echo "<td>" . $dt->telepon . "</td>";
                                // echo "</tr>";
                            // }
                            ?>
                            </tbody>
                        </table>
						-->
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="modalDetail" data-backdrop="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div class="row">
                    <div class="col-sm-12 text-center margin-bottom">
                        <img id="user-image" class="img-thumbnail img-responsive iimage" />
                    </div>
                </div>
                <table class="table table-striped">
                    <tr>
                        <td class="text-bold">NIK</td>
                        <td class="inik"></td>
                    </tr>
                    <tr>
                        <td class="text-bold">Nama</td>
                        <td class="inama"></td>
                    </tr>
                    <tr>
                        <td class="text-bold">Email</td>
                        <td class="iemail"></td>
                    </tr>
                    <tr>
                        <td class="text-bold">Bagian</td>
                        <td class="idepartemen"></td>
                    </tr>
                    <tr>
                        <td class="text-bold">Ext Telepon</td>
                        <td class="itelepon"></td>
                    </tr>
                </table>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

</div>
<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/infokaryawan/infokaryawan.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<style>
    .small-box .icon {
        top: -13px;
    }
    .form-control-static {
        padding-top: 0;
    }
    .modal-sm {
        width:400px;
    }
    .mb-10 {
        margin-bottom: 10px;
    }
</style>
