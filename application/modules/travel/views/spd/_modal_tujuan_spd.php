<div class="modal fade" role="dialog" id="modal-tujuan-spd" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Detail Tujuan Perjalanan Dinas</h4>
            </div>
            <div class="modal-body">
                <script type="text/template" id="tujuan_spd_template">
                    <tr class="template-trip">
                        <td width="5%" align="center">
                            <p class="form-control-static no-padding label_multi_no"></p>
                        </td>
                        <td width="25%">
                            <p class="form-control-static no-padding label_multi_tujuan"></p>
                        </td>
                        <td width="15%" align="center">
                            <p class="form-control-static no-padding label_multi_start"></p>
                        </td>
                        <td width="35%">
                            <p class="form-control-static no-padding label_multi_keperluan"></p>
                        </td>
                    </tr>
                </script>
                <table id="table-tujuan-spd"
                       class="table table-hover table-responsive table-bordered table-striped table-condensed"
                       data-page-length="10">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Tujuan</th>
                        <th>Keberangkatan</th>
                        <th>Keperluan</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="modal-footer text-center">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>