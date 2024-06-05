<div class="modal fade" role="dialog" id="modal-spk">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><span id="title">Tambah</span> Perjanjian</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="form-spk" enctype="multipart/form-data">
                    <input type="hidden" name="id_spk">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-md-4">Pabrik</label>
                                <div class="col-md-8">
									<!-- <input type="hidden" id="plant" name="plant" value="<?php echo $plant; ?>"/>
                                    <p class="form-control-static" id="plant"><?php echo $plant; ?></p> -->
                                    <select class="form-control select2" name="plant" id="plant" data-placeholder="Pilih Pabrik" required>
                                        <option></option>
                                        <?php foreach ($akses_plant as $plant) : ?>
                                            <option value="<?php echo $plant; ?>"><?php echo $plant; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4">Jenis Perjanjian</label>
                                <div class="col-md-8">
                                    <select class="form-control select2" name="id_jenis_spk" id="id_jenis_spk" data-placeholder="Pilih Jenis Perjanjian" required>
                                        <option></option>
                                        <?php foreach ($jenis_spk as $j) : ?>
                                            <option value="<?php echo $j->id_jenis_spk; ?>"><?php echo $j->jenis_spk; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div> 
                            <!-- <div class="form-group">
                                <label class="col-md-4">Nama Perjanjian</label>
                                <div class="col-md-8">
                                    <select class="form-control select2" name="id_nama_spk" id="id_nama_spk" data-placeholder="Pilih Nama Perjanjian" required data-selected=""></select>
                                </div>
                            </div> -->
                            <div class="form-group">
                                <label class="col-md-4">Perihal</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="perihal" name="perihal" required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4">Tanggal Perjanjian</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control datepicker" id="tanggal_perjanjian" name="tanggal_perjanjian" required/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4">Tanggal Berlaku Perjanjian</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control datepicker" id="tanggal_berlaku_spk" name="tanggal_berlaku_spk" required/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4">Tanggal Berakhir Perjanjian</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control datepicker" id="tanggal_berakhir_spk" name="tanggal_berakhir_spk" required/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4">Nomor Perjanjian</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" disabled id="nomor_spk" name="nomor_spk"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4">SPPKP</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="SPPKP" name="SPPKP" required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4">Jenis Vendor</label>
                                <div class="col-md-8">
                                    <select class="form-control select2" name="id_jenis_vendor" id="id_jenis_vendor" data-placeholder="Pilih Jenis Vendor" required>
                                        <option></option>
                                        <?php foreach ($jenis_vendor as $j) : ?>
                                            <option value="<?php echo $j->id_jenis_vendor; ?>"><?php echo $j->jenis_vendor; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">   
                                <label class="col-md-4">Nama Vendor</label>
								<div class="col-md-8">  
									<select class="form-control select2" name="lifnr" id="lifnr" required="required"></select>
									<input type="hidden" class="form-control" name="nama_vendor" id="nama_vendor">
								</div>
                            </div> 
                            <div class="form-group">   
                                <label class="col-md-4">Kualifikasi</label>  
								<div class="col-md-8"> 
									<select class="form-control select2" name="id_kualifikasi" id="id_kualifikasi" data-placeholder="Pilih Kualifikasi" required="required"></select>
									<input type="hidden" class="form-control" name="id_spesifikasi" id="id_spesifikasi">
								</div>
                            </div>    
                            <div class="form-group">
                                <label class="col-md-4">Kode Vendor</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="LIFNR" name="LIFNR" disabled>
                                    <!--<input type="hidden" class="form-control" id="LIFNR" name="LIFNR">-->
									
                                </div>
                            </div> 
                            <div class="form-group">  
                                <label class="col-md-4">Kota</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="CITY1" name="CITY1" disabled>
                                </div>  
                            </div> 
                            <div class="form-group">
                                <label class="col-md-4">Alamat</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="STRAS" name="STRAS" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </form> 
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger reset-form" type="reset"><i class="fa fa-undo"></i>&nbsp;Reset</button>
                <button name="save_spk" class="btn btn-success" type="button"><i class="fa fa-save"></i>&nbsp;Save
                </button>
            </div>
        </div>
    </div>
</div>