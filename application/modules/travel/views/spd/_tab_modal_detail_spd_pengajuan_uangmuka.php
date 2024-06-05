<fieldset class="fieldset-4px-rad fieldset-primary no-pad-top">
    <legend class="no-pad-top"><h4>Uang Muka</h4></legend>
    <div class="form-group no-padding">
        <label class="col-md-6" for="total_um">Uang Muka</label>
        <div class="col-md-6">
            <p class="form-control-static no-padding" id="label_total_um">
                <span class="label_total_um_jumlah numeric-label">0</span>
                <span class="label_total_um_currency">IDR</span>
            </p>
        </div>
    </div>
    <div id="div-uangmuka" class="hide">
        <div class="row">
            <div class="col-md-12">
                <script type="text/template" id="detail_uangmuka_template">
                    <tr>
                        <td>
                            <p class="uangmuka-label-expense"></p>
                        </td>
                        <td align="center">
                            <p class="uangmuka-label-currency"></p>
                        </td>
                        <td align="right">
                            <p class="uangmuka-label-rate numeric-label"></p>
                        </td>
                        <td align="center">
                            <p class="uangmuka-label-durasi"></p>
                        </td>
                        <td align="right">
                            <p class="uangmuka-label-jumlah numeric-label"></p>
                        </td>
                    </tr>
                </script>
            </div>
        </div>
        <table id="table-detail-uangmuka" class="table table-responsive table-bordered table-striped table-condensed">
            <thead>
            <tr>
                <th width="25%">Jenis Biaya</th>
                <th width="10%">Currency</th>
                <th width="10%">Rate</th>
                <th width="5%">Durasi (hari)</th>
                <th width="15%">Jumlah</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

    </div>
</fieldset>