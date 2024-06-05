<fieldset class="fieldset-4px-rad fieldset-success no-pad-top">
    <legend class="no-pad-top"><h4>Uang Muka Sebelumnya</h4></legend>
    <div id="div-um-sebelum-none" class="row hide">
        <div class="col-md-12 text-center">
            <p>Tidak ambil UM</p>
        </div>
    </div>
    <div id="div-um-sebelum" class="row hide">
        <div class="col-md-12">
            <script type="text/template" id="t_detail_uangmuka_template">
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
            <table id="table-uangmuka-tambah" class="table table-responsive table-bordered table-striped table-condensed">
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
    </div>
</fieldset>
<fieldset class="fieldset-4px-rad fieldset-primary no-pad-top">
    <legend class="no-pad-top"><h4>Uang Muka</h4></legend>
    <div class="form-group no-padding">
        <label class="col-md-6" for="total_umt">Uang Muka Baru</label>
        <div class="col-md-6">
            <div class="input-group">
                <input type="text"
                       name="total_umt" id="total_umt"
                       class="form-control text-right numeric"
                       numeric-min="0"
                       value="0">
                <span class="input-group-addon">IDR</span>
            </div>
        </div>
    </div>
    <div>
        <div class="row">
            <div class="col-md-12">
                <script type="text/template" id="t_uangmuka_template">
                    <tr>
                        <td>
                            <input type="hidden" name="uangmuka[{no}][id]" class="uangmukat-id">
                            <input type="hidden" name="uangmuka[{no}][fk]" class="uangmukat-fk">
                            <input type="hidden" name="uangmuka[{no}][kode_expense]" class="uangmukat-kode_expense">
                            <input type="hidden" name="uangmuka[{no}][rate]" class="uangmukat-rate">
                            <p class="uangmukat-label-expense"></p>
                        </td>
                        <td align="right">
                            <p class="uangmukat-label-rate numeric-label"></p>
                        </td>
                        <td align="center">
                            <input type="hidden" name="uangmuka[{no}][durasi]" class="uangmukat-durasi">
                            <p class="uangmukat-label-durasi"></p>
                        </td>
                        <td>
                            <div class="input-group">
                                <input type="hidden" name="uangmuka[{no}][currency]" class="uangmukat-currency">
                                <input id="uangmuka_{no}_jumlah"
                                       name="uangmuka[{no}][jumlah]" numeric-min="0"
                                       class="form-control uangmukat-jumlah text-right numeric">
                                <span class="input-group-addon uangmukat-label-currency"></span>
                            </div>
                        </td>
                    </tr>
                </script>
            </div>
        </div>
        <table id="table-uangmuka-tambah-baru" class="table table-responsive table-bordered table-striped table-condensed">
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
            <label class="col-md-6" for="sisa_umt">Sisa</label>
            <div class="col-md-6">
                <div class="input-group">
                    <input readonly disabled type="text"
                           name="sisa_umt" id="sisa_umt"
                           class="form-control text-right numeric"
                           numeric-leftover="0"
                           value="0">
                    <span class="input-group-addon">IDR</span>
                </div>
            </div>
        </div>
    </div>
</fieldset>