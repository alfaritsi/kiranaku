<fieldset class="fieldset-4px-rad fieldset-primary no-pad-top">
    <legend class="no-pad-top"><h4>Uang Muka</h4></legend>
    <div class="form-group no-padding">
        <label class="col-md-6" for="total_um">Uang Muka</label>
        <div class="col-md-6">
            <div class="input-group">
                <input readonly type="text"
                       name="total_um" id="total_um"
                       class="form-control text-right numeric"
                       numeric-min="0"
                       value="0">
                <span class="input-group-addon">IDR</span>
            </div>
        </div>
    </div>
    <div id="div-uangmuka" class="hide">
        <div class="row">
            <div class="col-md-12">
                <script type="text/template" id="uangmuka_template">
                    <tr>
                        <td>
                            <input type="hidden" name="uangmuka[{no}][id]" class="uangmuka-id">
                            <input type="hidden" name="uangmuka[{no}][fk]" class="uangmuka-fk">
                            <input type="hidden" name="uangmuka[{no}][kode_expense]" class="uangmuka-kode_expense">
                            <input type="hidden" name="uangmuka[{no}][rate]" class="uangmuka-rate">
                            <p class="uangmuka-label-expense"></p>
                        </td>
                        <td align="right">
                            <p class="uangmuka-label-rate numeric-label"></p>
                        </td>
                        <td align="center">
                            <input type="hidden" name="uangmuka[{no}][durasi]" class="uangmuka-durasi">
                            <p class="uangmuka-label-durasi"></p>
                        </td>
                        <td>
                            <div class="input-group">
                                <input type="hidden" name="uangmuka[{no}][currency]" class="uangmuka-currency">
                                <input id="uangmuka_{no}_jumlah"
                                       name="uangmuka[{no}][jumlah]" numeric-min="0"
                                       class="form-control uangmuka-jumlah text-right numeric">
                                <span class="input-group-addon uangmuka-label-currency"></span>
                            </div>
                        </td>
                    </tr>
                </script>
            </div>
        </div>
        <table id="table-uangmuka" class="table table-responsive table-bordered table-striped table-condensed">
            <thead>
            <tr>
                <th width="25%">Jenis Biaya</th>
                <th width="10%">Rate</th>
                <th width="5%">Durasi (hari)</th>
                <th width="15%">Jumlah</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
        <div class="form-group no-padding">
            <label class="col-md-6" for="sisa_um">Sisa</label>
            <div class="col-md-6">
                <div class="input-group">
                    <input readonly disabled type="text"
                           name="sisa_um" id="sisa_um"
                           class="form-control text-right numeric"
                           numeric-leftover="0"
                           value="0">
                    <span class="input-group-addon">IDR</span>
                </div>
            </div>
        </div>
    </div>
</fieldset>