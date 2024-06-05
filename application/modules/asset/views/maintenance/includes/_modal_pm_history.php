<!-- Modal PM tambah schedule -->
<div class="modal fade" id="history_modal" data-backdrop="static" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="col-sm-12">
                <div class="nav-tabs-custom" id="tabs-edit">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">History Asset</h4>
                    </div>
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab-history" data-toggle="tab">History PM</a></li>
                        <li><a href="#tab-history_perbaikan" class='perbaikan_fo' data-toggle="tab">Perbaikan</a></li>
                        <li class='movement_fo'><a href="#tab-history_asset" data-toggle="tab">Movement</a></li>
                    </ul>
                    <div class="modal-body">
                        <div class="tab-content">
                            <!--data-->
                            <div class="tab-pane active" id="tab-history">
                                <table class="table table-responsive" id="table-tab-history-pm">
                                    <thead>
                                    <th>Tanggal</th>
                                    <th>Jenis Maintenance</th>
                                    <th>Jadwal</th>
                                    <th>Pabrik</th>
                                    <th>Sub Lokasi</th>
                                    <th>Area</th>
                                    <th>User/Divisi</th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <!--data perbaikan-->
                            <div class="tab-pane" id="tab-history_perbaikan">
                                <table class="table table-responsive" id="table-tab-history-perbaikan">
                                    <thead>
                                    <th>No</th>
                                    <th>Tgl Mulai</th>
                                    <th>Tgl Selesai</th>
									<th>Item Perbaikan</th>
                                    <th>User</th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <!--data perubahan-->
                            <div class="tab-pane" id="tab-history_asset">
                                <table class="table table-responsive" id="table-tab-history-asset">
                                    <thead>
                                    <th>No</th>
                                    <th>Jenis Perubahan</th>
                                    <th>Tanggal</th>
									<th>Status Awal</th>
                                    <th>Status Akhir</th>
                                    <th>Alasan</th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>