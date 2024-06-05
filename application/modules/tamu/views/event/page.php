<?php $this->load->view('header') ?>
<!-- customs apps css -->

<div class="content-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h1 class="box-title">Data Event</h1>
                        <div class="btn-group btn-group-sm pull-right">
                            <button class="btn btn-success add_event"><i class="fa fa-plus-square"></i> &nbsp Buat Event</button>
                        </div>
                    </div>

                    <div class="box-body hidden">
			          	<div class="row">
							<div class="col-md-2">
								<div class="form-group">
									<label>Tanggal Awal :</label>
									<div class="input-group date">
										<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
										<input type="text" class="form-control datePicker" style="padding: 10px;" placeholder="dd.mm.yyyy" value="<?php echo date('d.m.Y');?>" id="filter_from" name="filter_from" readonly>
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>Tanggal Akhir :</label>
									<div class="input-group date">
										<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
										<div id="div_filter_to">
										<input type="text" class="form-control datePicker" style="padding: 10px;" placeholder="dd.mm.yyyy" value="" id="filter_to" name="filter_to" readonly>
										</div>
									</div>
								</div>
							</div>
		            	</div>
		            </div>					
					<!-- /.box-filter -->

                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered table-hover table-striped" id="sspTable" data-ordering="true" data-scrollx="true" data-bautowidth="true" data-pagelength="25">
                                    <thead>
                                        <th>Tanggal</th>
                                        <th>Nama Event</th>
                                        <th>Waktu Mulai</th>
                                        <th>Waktu Selesai</th>
                                        <th>PIC</th>
                                        <th>&nbsp;</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                            foreach($event as $dt){
                                                echo "<tr>";
                                                echo "<td>".$dt->tanggal_format."</td>";
                                                echo "<td>".$dt->nama_event."</td>";
                                                echo "<td>".$dt->waktu_mulai_format."</td>";
                                                echo "<td>".$dt->waktu_selesai_format."</td>";
                                                echo "<td>".$dt->nama_pic."</td>";
                                                echo "<td>
                                                    <div class='input-group-btn'>
                                                        <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
                                                        <ul class='dropdown-menu pull-right'>";
                                                            echo "<li><a href='".base_url()."tamu/event/detail/".$dt->id."' class='edit' data-id_event='".$dt->id."'><i class='fa fa-search'></i> Data Peserta</a></li>";
                                                            // echo "<li><a href='javascript:void(0)' class='edit' data-id_event='".$dt->id."'><i class='fa fa-pencil-square-o'></i> Edit</a></li>";
                                                            echo "<li><a href='javascript:void(0)' class='delete' data-id_event='".$dt->id."' data-action='delete'><i class='fa fa-trash'></i> Hapus</a></li>";
                                                echo " 	</ul>
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
            </div>
        </div>

        <!--modal konfirmasi-->
        <div class="modal fade" id="modal_event" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-sg" role="document">
                <div class="modal-content">
                    <div class="col-sm-12">
                        <div class="modal-content">
                            <form role="form" class="form-master-event">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Master Event</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">	
                                        <label for="nama">Nama Event</label>
                                        <input type="text" class="form-control" name="nama_event" id="nama_event" placeholder="Nama Event" required>
                                    </div>
                                    <div class="form-group">	
                                        <label for="taggal">Tanggal Event</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input type="text" class="form-control datePicker" style="padding: 10px;" placeholder="dd.mm.yyyy" value="<?php echo date('d.m.Y');?>" id="tanggal_event" name="tanggal_event" readonly required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">	
                                                <label for="jam_mulai">Jam Mulai</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="jam_mulai" id="jam_mulai" placeholder="Jam Mulai" required>
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-clock-o"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">	
                                                <label for="jam_selesai">Jam Selesai</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="jam_selesai" id="jam_selesai" placeholder="Jam Selesai" required>
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-clock-o"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">	
                                        <label for="taggal">PIC</label>
                                        <select class="form-control" name="set_nik" id="set_nik" data-placeholder="Cari karyawan (nama atau nik)" required="required">
                                            <option></option>
                                        </select>
                                        <input type="hidden" class="form-control" name="nik_pic" placeholder="Nama User"  required="required">
                                    </div>
                                    <div class="form-group">	
                                        <label for="nama">Pesan</label>
                                        <textarea type="text" class="form-control" name="pesan" id="pesan" placeholder="Pesan/Catatan Khusus Event"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <input id="id_tamu" name="id_tamu" type="hidden">
                                    <button id="btn_save" type="button" class="btn btn-primary" name="action_btn_save">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>	
        </div>
    </div>
</div>

<?php $this->load->view('footer') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker3.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/moment/bootstrap-datetimepicker.min.css"/>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/moment/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/apps/js/tamu/event/page.js"></script>