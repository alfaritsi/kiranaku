<fieldset class="fieldset-4px-rad fieldset-warning no-pad-top animated fadeIn">
    <legend class="no-pad-top"><h4>Detail biaya</h4></legend>
    <div id="div-biaya" class="animated fadeIn hide">
        <div class="row">
            <div class="col-md-12">
                <script type="text/template" id="biaya_template">
                    <tr class="template-trip">
                        <td width="25%">
                            <p class="form-control-static biaya_tanggal"></p>
                        </td>
                        <td width="25%">
                            <p class="form-control-static biaya_jenis"></p>
                        </td>
                        <td width="15%">
                            <p class="form-control-static biaya_keterangan"></p>
                        </td>
                        <td width="20%">
                            <p class="form-control-static biaya_jumlah text-right">
                                <span class="jumlah numeric-label"></span>
                                <span class="currency"></span>
                            </p>
                        </td>
                        <td width="50">
                            <a class="btn btn-default btn-sm"
                               target="_blank" data-fancybox="gallery"
                            ><i class="fa fa-search"></i></a>
                        </td>
                    </tr>
                </script>
                <table id="table-detail-biaya" class="table table-bordered table-striped table-condensed">
                    <thead>
                    <tr>
                        <th width="15%">Tanggal</th>
                        <th width="25%">Biaya</th>
                        <th width="15%">Keterangan</th>
                        <th width="20%">Jumlah</th>
                        <th width="10%"><i class="fa fa-image"></i></th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <hr/>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group no-padding">
                    <label class="col-md-4" for="total_biaya">Jumlah Biaya</label>
                    <div class="col-md-8">
                        <p class="form-control-static total_biaya">
                            <span class="jumlah numeric-label"></span>
                            <span class="currency"></span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group no-padding">
                    <label class="col-md-4" for="total_um">Uang muka</label>
                    <div class="col-md-8">
                        <p class="form-control-static uang_muka">
                            <span class="jumlah numeric-label"></span>
                            <span class="currency"></span>
                        </p>
                    </div>
                </div>
                <div class="form-group no-padding">
                    <label class="col-md-4" for="total_bayar">Dibayarkan</label>
                    <div class="col-md-8">
                        <p class="form-control-static total_bayar">
                            <span class="jumlah numeric-label"></span>
                            <span class="currency"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</fieldset>