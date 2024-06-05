<div class="modal fade" role="dialog" id="modal-detail-so" data-backdrop="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Detail SO</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <fieldset class="fieldset-info">
                            <legend>Header</legend>
                            <div class="row">
                                <div class="col-md-3"><strong>Pabrik</strong></div>
                                <div class="col-md-9" id="id_plant">ABL1</div>
                            </div>
                            <div class="row">
                                <div class="col-md-3"><strong>NO PO</strong></div>
                                <div class="col-md-9" id="no_so">SO#100002</div>
                            </div>
                            <div class="row">
                                <div class="col-md-3"><strong>Tanggal PO</strong></div>
                                <div class="col-md-9" id="tanggal">01.01.2019</div>
                            </div>


                        </fieldset>

                    </div>
                </div>
                <fieldset class="fieldset-success">
                    <legend>Detail Items</legend>
                    <table class="table table-responsive table-striped table-bordered clearfix my-datatable-extends-order"
                           id="table-items">
                        <thead>
                        <th width="5%">No</th>
                        <th>No Item</th>
                        <th>Kode SAP</th>
                        <th>Deskripsi</th>
                        <th>UOM</th>
                        <th>Tgl Delivery</th>
                        <th>Qty Order</th>
                        <th>Qty Stok</th>
                        <th width="20%">No IO</th>
                        </thead>
                    </table>
                </fieldset>
            </div>
        </div>
    </div>
</div>