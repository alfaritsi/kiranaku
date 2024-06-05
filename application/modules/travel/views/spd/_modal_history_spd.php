<div class="modal fade" role="dialog" id="modal-history-spd" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">History Perjalanan Dinas</h4>
            </div>
            <div class="modal-body no-padding">
                <script type="text/template" id="history_spd_template">
                    <tr class="template-trip">
                        <td width="10%" align="center">
                            <p class="form-control-static no-padding label_tanggal"></p>
                        </td>
                        <td width="30%">
                            <p class="form-control-static no-padding label_action"></p>
                        </td>
                        <td width="15%">
                            <p class="form-control-static no-padding label_remark"></p>
                        </td>
                        <td width="30%">
                            <p class="form-control-static no-padding label_comment"></p>
                        </td>
                        <td width="15%" align="center">
                            <p class="form-control-static no-padding label_by"></p>
                        </td>
                    </tr>
                </script>
                <table id="table-history-spd"
                       class="table table-responsive table-bordered table-striped table-condensed">
                    <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Action</th>
                        <th>Remark</th>
                        <th>Komentar</th>
                        <th>By</th>
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