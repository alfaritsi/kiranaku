<?php $this->load->view('header') ?>
<!-- customs apps css -->

<div class="content-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h1 class="box-title">Master Barang</h1>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered table-hover table-striped" id="sspTable" data-ordering="true" data-scrollx="true" data-bautowidth="true" data-pagelength="10">
                                    <thead>
                                        <th>Kode</th>
                                        <th>Deskripsi 1</th>
                                        <th>Deskripsi 2</th>
                                        <th>Tipe</th>
                                        <th>Asset Class</th>
                                        <th>G/L Account</th>
                                        <th>Cost Center</th>
                                        <th>#</th>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal_master_barang" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-sg" role="document">
                <div class="modal-content">
                    <div class="col-sm-12">
                        <div class="modal-content">
                            <form role="form" id="form-set-data" enctype="multipart/form-data">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Master Barang</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="kode_barang">Kode Barang</label>
                                        <input type="text" class="form-control" name="kode_barang" id="kode_barang" required readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="nama_barang">Nama Barang</label>
                                        <input type="text" class="form-control" name="nama_barang" id="nama_barang" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="tipe_barang">Tipe</label>
                                        <select class="form-control" name="tipe_barang" id="tipe_barang" required>
                                            <option value="">Pilih Tipe</option>
                                            <option value="A">Asset</option>
                                            <option value="I">Inventory</option>
                                            <option value="K">Expense</option>
                                        </select>
                                    </div>
                                    <div class="form-group additional hidden" id="add_asset_class">
                                        <label for="harga">Asset Class</label>
                                        <select class="form-control select2" name="asset_class" id="asset_class">
                                        </select>
                                    </div>
                                    <div class="form-group additional hidden" id="add_gl_account">
                                        <label for="harga">G/L Account</label>
                                        <select class="form-control" name="gl_account" id="gl_account"></select>
                                    </div>
                                    <div class="form-group additional hidden" id="add_cost_center">
                                        <label for="harga">Cost Center</label>
                                        <select class="form-control select2" name="cost_center[]" id="cost_center" multiple></select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <input name="id_barang" type="hidden" value="">
                                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                                    <button id="btn_save_item" type="button" class="btn btn-success" name="action_btn_save">Submit</button>
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
<script type="text/javascript" src="<?php echo base_url() ?>assets/apps/js/plantation/master/barang.js"></script>